<?php
// index.php

// Cấu hình đường dẫn file CSV
$filename = '65HTTT_Danh_sach_diem_danh.csv';

// 1. Kiểm tra file tồn tại
if (!file_exists($filename)) {
    // Tạo nội dung mẫu nếu file không tồn tại
    $sampleContent = "username,password,lastname,firstname,city,email,course1\n" .
                     "2351160500,123456,Nguyen,A,Hanoi,a@e.tlu.edu.vn,CSE485";
    file_put_contents($filename, $sampleContent);
    echo "<div style='color:red; padding:20px; text-align:center;'>⚠️ Không tìm thấy file <b>$filename</b>. Hệ thống đã tự tạo file mẫu. Vui lòng tải lại trang!</div>";
    exit;
}

// 2. Hàm đọc file CSV an toàn
if (!function_exists('readCSV')) {
    function readCSV($filename) {
        $data = [];
        $headers = [];
        
        if (($handle = fopen($filename, "r")) !== FALSE) {
            // Xử lý BOM (Byte Order Mark) nếu file lưu UTF-8 có BOM
            // Đọc 3 byte đầu để kiểm tra
            $bom = fread($handle, 3);
            if ($bom != "\xEF\xBB\xBF") {
                // Nếu không phải BOM, quay lại đầu file
                rewind($handle);
            }
            
            // Lấy dòng tiêu đề (Headers)
            if (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $headers = $row;
            }

            // Lấy dữ liệu các dòng tiếp theo
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Chỉ lấy dòng có dữ liệu (tránh dòng trống cuối file)
                if (array_filter($row)) {
                    // Kết hợp header và row để dễ truy xuất bằng tên cột (nếu số lượng cột khớp nhau)
                    if (count($headers) === count($row)) {
                        $data[] = array_combine($headers, $row);
                    } else {
                        // Nếu không khớp cột, lưu mảng thường và báo warning (tùy chọn)
                        $data[] = $row;
                    }
                }
            }
            fclose($handle);
        }
        return ['headers' => $headers, 'data' => $data];
    }
}

// --- CHẠY LOGIC ---
$csvResult = readCSV($filename);
$headers = $csvResult['headers'];
$students = $csvResult['data'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sinh Viên</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; padding: 20px; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #f1f3f5; }
        h1 { margin: 0; color: #2c3e50; font-size: 1.8rem; }
        
        .stats { color: #6c757d; font-weight: 500; }
        
        /* Table Styles */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        
        th { background-color: #3b82f6; color: white; padding: 12px 15px; text-align: left; font-weight: 600; white-space: nowrap; position: sticky; top: 0; }
        td { padding: 12px 15px; border-bottom: 1px solid #e9ecef; color: #495057; }
        
        tr:hover { background-color: #f1f8ff; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        .empty-state { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
        
        .btn-action {
            display: inline-block;
            padding: 10px 20px;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 20px;
        }
        .btn-action:hover { background-color: #059669; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>📄 Dữ liệu từ file CSV</h1>
        <div class="stats">Tổng số: <strong><?php echo count($students); ?></strong> sinh viên</div>
    </header>

    <div class="table-responsive">
        <?php if (!empty($students)): ?>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <?php foreach ($headers as $colName): ?>
                            <th><?php echo htmlspecialchars(ucfirst($colName)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <?php 
                            // Nếu $student là mảng kết hợp (key là header)
                            if (array_keys($student) !== range(0, count($student) - 1)) {
                                foreach ($headers as $colName) {
                                    echo "<td>" . htmlspecialchars($student[$colName] ?? '') . "</td>";
                                }
                            } else {
                                // Nếu là mảng thường (do lỗi không khớp cột)
                                foreach ($student as $cell) {
                                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                                }
                            }
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                Không có dữ liệu nào trong file CSV.
            </div>
        <?php endif; ?>
    </div>

    <!-- Nút giả lập hành động tiếp theo -->
    <div style="text-align: right;">
        <a href="#" class="btn-action" onclick="alert('Tính năng đang phát triển: Lưu vào CSDL...')">💾 Lưu vào CSDL</a>
    </div>
</div>

</body>
</html>