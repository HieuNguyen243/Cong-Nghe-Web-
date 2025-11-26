<?php
// Mảng dữ liệu gốc (Hardcode)
$flowers = [
  ["name" => "DaYenThao", "description" => "Dạ yến thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở."],
  ["name" => "DongTien", "description" => "Hoa đồng tiền thích hợp để trồng trong mùa xuân và đầu mùa hè, hoa to rực rỡ."],
  ["name" => "HoaGiay", "description" => "Hoa giấy có mặt ở hầu khắp mọi nơi, dễ trồng, không tốn quá nhiều công chăm sóc."],
  ["name" => "CamTuCau", "description" => "Cẩm tú cầu thường mọc thành bụi có hoa nở to thành từng chùm, thích hợp mùa hè."]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Demo - Reset khi F5</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f6f9; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        
        /* Form style */
        .form-group { margin-bottom: 10px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        
        /* Table style */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #343a40; color: white; }
        img { width: 50px; height: 50px; object-fit: cover; }
        
        /* Button colors */
        .btn { padding: 5px 10px; cursor: pointer; color: white; border: none; border-radius: 3px; }
        .btn-add { background-color: #28a745; padding: 10px 20px; font-size: 16px; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-delete { background-color: #dc3545; }
    </style>
</head>
<body>

<div class="container">
    <h1>🌻 Quản Lý Hoa (Chế độ Demo)</h1>
    <p style="text-align:center; color: #666; font-style: italic;">
        Lưu ý: Mọi thao tác dưới đây chỉ là giả lập. Dữ liệu sẽ khôi phục khi tải lại trang.
    </p>

    <div style="background: #e9ecef; padding: 15px; border-radius: 5px;">
        <div class="form-group">
            <label>Tên hoa (Mã):</label>
            <input type="text" id="inputName" placeholder="VD: HoaHong">
        </div>
        <div class="form-group">
            <label>Mô tả:</label>
            <textarea id="inputDesc" rows="2" placeholder="Nhập mô tả..."></textarea>
        </div>
        <button onclick="addFlower()" class="btn btn-add">Thêm vào bảng</button>
    </div>

    <table id="flowerTable">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên Hoa</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Dùng PHP để in ra dữ liệu gốc ban đầu
            foreach ($flowers as $index => $flower) {
                $stt = $index + 1;
                echo "<tr>";
                echo "<td>$stt</td>";
                echo "<td><img src='./images/{$flower['name']}.webp' onerror=\"this.src='https://via.placeholder.com/50'\"></td>";
                echo "<td class='name-cell'>{$flower['name']}</td>";
                echo "<td class='desc-cell'>{$flower['description']}</td>";
                // Nút xóa gọi hàm JavaScript deleteRow(this)
                echo "<td>
                        <button class='btn btn-delete' onclick='deleteRow(this)'>Xóa</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    // --- 1. CHỨC NĂNG XÓA ---
    function deleteRow(btn) {
        if (confirm('Bạn chắc chắn muốn xóa dòng này? (Sẽ khôi phục khi F5)')) {
            // Tìm thẻ <tr> chứa cái nút vừa bấm và xóa nó đi
            var row = btn.parentNode.parentNode;
            row.remove();
            updateSTT(); // Cập nhật lại số thứ tự cho đẹp
        }
    }

    // --- 2. CHỨC NĂNG THÊM MỚI ---
    function addFlower() {
        var name = document.getElementById('inputName').value;
        var desc = document.getElementById('inputDesc').value;

        if(name === '') {
            alert('Vui lòng nhập tên hoa!');
            return;
        }

        var table = document.getElementById('flowerTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);

        // Tạo nội dung cho dòng mới
        newRow.innerHTML = `
            <td></td>
            <td><img src="./images/${name}.webp" onerror="this.src='https://via.placeholder.com/50'" style="width:50px; height:50px;"></td>
            <td class="name-cell">${name}</td>
            <td class="desc-cell">${desc}</td>
            <td><button class="btn btn-delete" onclick="deleteRow(this)">Xóa</button></td>
        `;

        // Reset ô nhập liệu
        document.getElementById('inputName').value = '';
        document.getElementById('inputDesc').value = '';
        
        updateSTT();
    }

    // --- HÀM CẬP NHẬT SỐ THỨ TỰ (STT) ---
    function updateSTT() {
        var table = document.getElementById('flowerTable');
        var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
            rows[i].getElementsByTagName('td')[0].innerText = i + 1;
        }
    }
</script>

</body>
</html>