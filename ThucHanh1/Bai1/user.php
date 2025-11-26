<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$flowers = [
  ["name" => "DaYenThao", "description" => "Dạ yến thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở. Hoa có thể nở rực quanh năm, kể cả tiết trời se lạnh của mùa xuân hay cả những ngày nắng nóng cao điểm của mùa hè. Dạ yến thảo được trồng ở chậu treo nơi cửa sổ hay ban công, dáng hoa mềm mại, sắc màu đa dạng nên được yêu thích vô cùng."],
  ["name" => "DongTien", "description" => "Hoa đồng tiền thích hợp để trồng trong mùa xuân và đầu mùa hè, khi mà cường độ ánh sáng chưa quá mạnh. Cây có hoa to, nở rộ rực rỡ, lại khá dễ trồng và chăm sóc nên sẽ là lựa chọn thích hợp của bạn trong tiết trời này. Về mặt ý nghĩa, hoa đồng tiền cũng tượng trưng cho sự sung túc, tình yêu và hạnh phúc viên mãn."],
  ["name" => "HoaGiay", "description" => "Hoa giấy có mặt ở hầu khắp mọi nơi trên đất nước ta, thích hợp với nhiều điều kiện sống khác nhau nên rất dễ trồng, không tốn quá nhiều công chăm sóc nhưng thành quả mang lại sẽ khiến bạn vô cùng hài lòng. Hoa giấy mỏng manh nhưng rất lâu tàn, với nhiều màu như trắng, xanh, đỏ, hồng, tím, vàng… cùng nhiều sắc độ khác nhau. Vào mùa khô hanh, hoa vẫn tươi sáng trên cây khiến ngôi nhà luôn luôn bừng sáng."],
  ["name" => "CamTuCau", "description" => "Cẩm tú cầu là loại cây thường mọc thành bụi có hoa nở to thành từng chùm và đặc biệt thích hợp với mùa hè. Hoa ưa ánh nắng mặt trời từ bình minh cho đến khi lặn xuống mỗi chiều tà. Hoa có nhiều màu sắc như trắng, tím, hồng, xanh rất nhẹ nhàng. Hoa thích hợp trồng nơi sân vườn rộng rãi hoặc chậu nhỏ để trang trí nhà ở."]
]
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Hoa</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
      <h1>Welcome user to Hoa!</h1>
      <div>
        <?php
          $name = $flowers[0]["name"];
          $desc = $flowers[0]["description"];

          echo "<h2>$name</h2>";
          echo "<p>$desc</p>";
          echo "<img src=\"./images/$name.webp\" alt=\"\">";
        ?>
      </div>

      <div>
        <?php
          $name = $flowers[1]["name"];
          $desc = $flowers[1]["description"];

          echo "<h2>$name</h2>";
          echo "<p>$desc</p>";
          echo "<img src=\"./images/$name.webp\" alt=\"\">";
        ?>
      </div>

      <div>
        <?php
          $name = $flowers[2]["name"];
          $desc = $flowers[2]["description"];

          echo "<h2>$name</h2>";
          echo "<p>$desc</p>";
          echo "<img src=\"./images/$name.webp\" alt=\"\">";
        ?>
      </div>
      <div>
        <?php
          $name = $flowers[3]["name"];
          $desc = $flowers[3]["description"];

          echo "<h2>$name</h2>";
          echo "<p>$desc</p>";
          echo "<img src=\"./images/$name.webp\" alt=\"\">";
        ?>
      </div>
    </body>
</html>