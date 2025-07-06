<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Сохранение заказа
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'nullable|integer',
                'product_name' => 'required|string',
                'product_price' => 'nullable|string',
                'customer_name' => 'required|string',
                'customer_phone' => 'required|string',
                'customer_email' => 'nullable|string',
                'customer_address' => 'nullable|string',
                'status' => 'nullable|string|in:Виконується,Виконано,Скасовано,Очікується',
            ]);
            // Приведение product_price к строке (если число)
            if (isset($data['product_price']) && !is_string($data['product_price'])) {
                $data['product_price'] = strval($data['product_price']);
            }
            if (!isset($data['status'])) {
                $data['status'] = 'Очікується';
            }
            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            }
            Order::create($data);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Order create error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // Получение заказов для админки
    public function index()
    {
        $orders = Order::latest()->get();
        return response()->json($orders);
    }

    // Получить детали одного заказа для PDF
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'product_name' => $order->product_name,
                'product_price' => $order->product_price,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'customer_address' => $order->customer_address,
                'created_at' => $order->created_at,
                'status' => $order->status,
            ]
        ]);
    }

    // Обновление статуса заказа
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|string|in:Виконується,Виконано,Скасовано,Очікується',
        ]);
        $order->status = $data['status'];
        $order->save();
        return response()->json(['success' => true]);
    }

    public function pdf($id)
    {
        $order = \App\Models\Order::find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        // Создание TCPDF с поддержкой UTF-8
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Slava-service');
        $pdf->SetAuthor('Slava-service');
        $pdf->SetTitle('Замовлення #' . $order->id);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 12);

        // Генерация HTML с правильной кодировкой
        $html = $this->generateOrderHTML($order);

        $pdf->writeHTML($html, true, false, true, false, '');

        return response($pdf->Output('order_'.$order->id.'.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="order_'.$order->id.'.pdf"'
        ]);
    }

    private function generateOrderHTML($order)
    {
        $customerName = mb_convert_encoding($order->customer_name, 'UTF-8');
        $productName = mb_convert_encoding($order->product_name, 'UTF-8');
        $customerAddress = mb_convert_encoding($order->customer_address ?? '', 'UTF-8');

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: DejaVu Sans, sans-serif; }
                .header { text-align: center; margin-bottom: 30px; }
                .order-info { margin-bottom: 20px; }
                .customer-info { margin-bottom: 20px; }
                .product-info { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Замовлення №' . $order->id . '</h1>
                <p>Дата: ' . date('d.m.Y H:i', strtotime($order->created_at)) . '</p>
            </div>
            
            <div class="customer-info">
                <h3>Інформація про клієнта:</h3>
                <p><strong>Ім\'я:</strong> ' . htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Телефон:</strong> ' . htmlspecialchars($order->customer_phone, ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($order->customer_email ?? '', ENT_QUOTES, 'UTF-8') . '</p>
                <p><strong>Адреса:</strong> ' . htmlspecialchars($customerAddress, ENT_QUOTES, 'UTF-8') . '</p>
            </div>
            
            <div class="product-info">
                <h3>Інформація про товар:</h3>
                <table>
                    <tr>
                        <th>Назва товару</th>
                        <th>Ціна</th>
                        <th>Статус</th>
                    </tr>
                    <tr>
                        <td>' . htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') . '</td>
                        <td>' . htmlspecialchars($order->product_price ?? '', ENT_QUOTES, 'UTF-8') . ' грн</td>
                        <td>' . htmlspecialchars($order->status ?? 'Очікується', ENT_QUOTES, 'UTF-8') . '</td>
                    </tr>
                </table>
            </div>
        </body>
        </html>';

        return $html;
    }

    public function generatePdf($id)
    {
        $order = Order::findOrFail($id);

        $pdf = new \TCPDF();

        $pdf->AddPage();

        // Установка кириллического шрифта
        $pdf->SetFont('dejavusans', '', 12);

        $html = '
            <h1>Замовлення №' . $order->id . '</h1>
            <p><strong>Назва товару:</strong> ' . htmlspecialchars($order->product_name, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Ціна:</strong> ' . htmlspecialchars($order->product_price, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Імʼя клієнта:</strong> ' . htmlspecialchars($order->customer_name, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Телефон:</strong> ' . htmlspecialchars($order->customer_phone, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Email:</strong> ' . htmlspecialchars($order->customer_email, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Адреса:</strong> ' . htmlspecialchars($order->customer_address, ENT_QUOTES, 'UTF-8') . '</p>
            <p><strong>Дата замовлення:</strong> ' . $order->created_at->format('d.m.Y H:i:s') . '</p>
        ';

        $pdf->writeHTML($html);

        $pdf->Output('order_' . $order->id . '.pdf', 'D'); // 'D' для скачивания, 'I' для открытия в браузере
    }
}