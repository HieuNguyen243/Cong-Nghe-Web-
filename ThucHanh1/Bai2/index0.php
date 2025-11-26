<?php
// Đường dẫn tới file (đảm bảo file Quiz.txt nằm cùng thư mục)
$filename = 'Quiz.txt';

// Kiểm tra file có tồn tại không
if (!file_exists($filename)) {
    die("Lỗi: Không tìm thấy file $filename");
}

$content = file_get_contents($filename);

// --- HÀM XỬ LÝ ---
function parseQuizData($content) {
    // $content = preg_replace("#\#", '', $content);

    $lines = explode("\n", $content);
    $questions = [];
    
    $currentQuestion = [
        'question' => '',
        'options' => [],
        'answer' => ''
    ];
    
    $isCollectingQuestion = true;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // Xử lý dòng ANSWER
        if (strpos($line, 'ANSWER:') === 0) {
            $ansStr = trim(substr($line, 7));
            // Nếu có dấu phẩy, chuyển thành mảng
            if (strpos($ansStr, ',') !== false) {
                // Tách chuỗi thành mảng dựa trên dấu phẩy
                $arr = explode(',', $ansStr);
                // Xóa khoảng trắng thừa từng phần tử
                $currentQuestion['answer'] = array_map('trim', $arr);
            } else {
                $currentQuestion['answer'] = $ansStr;
            }

            $questions[] = $currentQuestion;
            
            // Reset cho câu tiếp theo
            $currentQuestion = ['question' => '', 'options' => [], 'answer' => ''];
            $isCollectingQuestion = true; 
            continue;
        }

        // Xử lý dòng Đáp án (A. B. C. D. E.)
        if (preg_match('/^([A-E])\.\s*(.*)/', $line, $matches)) {
            $key = $matches[1]; 
            $value = $matches[2]; 
            $currentQuestion['options'][$key] = $value;
            $isCollectingQuestion = false;
            continue;
        }

        // Xử lý dòng Câu hỏi
        if ($isCollectingQuestion) {
            $currentQuestion['question'] .= ($currentQuestion['question'] === '' ? '' : ' ') . $line;
        }
    }
    return $questions;
}

// --- CHẠY HÀM ---
$quizArray = parseQuizData($content);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách câu hỏi</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .quiz-item { margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .question { font-weight: bold; color: #007bff; }
        .correct { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Danh sách câu hỏi đã xử lý</h1>
    
    <?php foreach ($quizArray as $index => $item): ?>
        <div class="quiz-item">
            <p class="question">Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($item['question']); ?></p>
            <ul>
                <?php foreach ($item['options'] as $key => $opt): ?>
                    <li><strong><?php echo $key; ?>.</strong> <?php echo htmlspecialchars($opt); ?></li>
                <?php endforeach; ?>
            </ul>
            
            <p>Đáp án đúng: 
                <span class="correct">
                <?php 
                    $ans = $item['answer'];
                    if (is_array($ans)) {
                        // Nếu là mảng (ví dụ câu chọn nhiều đáp án), nối lại bằng dấu phẩy
                        echo implode(", ", $ans); 
                    } else {
                        // Nếu là chuỗi bình thường
                        echo $ans; 
                    }
                ?>
                </span>
            </p>
        </div>
    <?php endforeach; ?>

</body>
</html>