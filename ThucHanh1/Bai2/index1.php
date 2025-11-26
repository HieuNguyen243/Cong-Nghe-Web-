<?php
// index.php

// Cấu hình đường dẫn file
$filename = 'Quiz.txt';

// 1. Kiểm tra file tồn tại
if (!file_exists($filename)) {
    // Tạo nội dung mẫu nếu file không tồn tại để tránh lỗi trắng trang
    $sampleContent = "Câu hỏi mẫu (Vui lòng tạo file Quiz.txt)?\nA. Sai\nB. Đúng\nANSWER: B";
    file_put_contents($filename, $sampleContent);
    echo "<div style='color:red; padding:20px; text-align:center;'>⚠️ Không tìm thấy file <b>Quiz.txt</b>. Hệ thống đã tự tạo file mẫu. Vui lòng tải lại trang!</div>";
    exit;
}

// 2. Đọc file an toàn
// Dùng @ để chặn warning nếu lỗi quyền truy cập, và kiểm tra false
$lines = @file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lines === false) {
    die("Lỗi: Không thể đọc nội dung file $filename. Hãy kiểm tra quyền truy cập (Permission).");
}

// 3. Định nghĩa hàm xử lý (Kiểm tra function_exists để tránh lỗi redeclare nếu include lại)
if (!function_exists('parseQuizData')) {
    function parseQuizData($lines) {
        $questions = [];
        $currentQuestion = [
            'question' => '',
            'options' => [],
            'answer' => []
        ];
        
        $isCollectingQuestion = true;

        foreach ($lines as $line) {
            // Xử lý BOM character nếu file lưu UTF-8 with BOM (lỗi phổ biến trên Windows)
            $line = trim($line, "\xEF\xBB\xBF \t\n\r\0\x0B"); 
            
            if (empty($line)) continue;

            // -- Xử lý dòng ANSWER (Kết thúc 1 câu) --
            if (strpos($line, 'ANSWER:') === 0) {
                $ansStr = trim(substr($line, 7));
                $currentQuestion['answer'] = array_map('trim', explode(',', $ansStr));
                
                // Lưu câu hỏi hoàn chỉnh
                if (!empty($currentQuestion['question'])) {
                    $questions[] = $currentQuestion;
                }
                
                // Reset
                $currentQuestion = ['question' => '', 'options' => [], 'answer' => []];
                $isCollectingQuestion = true; 
                continue;
            }

            // -- Xử lý đáp án (A. B. C...) --
            if (preg_match('/^([A-Z])[\.\)]\s*(.*)/', $line, $matches)) {
                $key = $matches[1]; 
                $value = $matches[2]; 
                $currentQuestion['options'][$key] = $value;
                $isCollectingQuestion = false;
                continue;
            }

            // -- Xử lý câu hỏi --
            if ($isCollectingQuestion) {
                $currentQuestion['question'] .= ($currentQuestion['question'] === '' ? '' : ' ') . $line;
            }
        }
        return $questions;
    }
}

// --- CHẠY LOGIC ---
$quizArray = parseQuizData($lines);

// Xử lý POST (Chấm điểm)
$showResult = false;
$userScore = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $showResult = true;
    foreach ($quizArray as $index => $item) {
        $userAns = isset($_POST['q' . $index]) ? $_POST['q' . $index] : [];
        
        // So sánh mảng đáp án (Logic: Phải chọn ĐỦ và ĐÚNG mới được điểm)
        $diff1 = array_diff($item['answer'], $userAns); // Có đáp án đúng nào bị thiếu không?
        $diff2 = array_diff($userAns, $item['answer']); // Có chọn thừa đáp án sai nào không?
        
        if (empty($diff1) && empty($diff2) && !empty($userAns)) {
            $userScore++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 20px; margin: 0; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #1a73e8; margin-bottom: 30px; }
        
        .quiz-item { margin-bottom: 25px; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #fff; }
        .question { font-weight: bold; margin-bottom: 15px; font-size: 1.1em; color: #202124; }
        
        .option-label { display: flex; align-items: center; margin: 8px 0; cursor: pointer; padding: 10px; border-radius: 6px; border: 1px solid transparent; transition: all 0.2s; }
        .option-label:hover { background: #f8f9fa; border-color: #dadce0; }
        .option-label input { margin-right: 12px; transform: scale(1.2); cursor: pointer; }
        
        /* Màu sắc kết quả */
        .correct-opt { background-color: #e6fffa; color: #047857; border-color: #047857; font-weight: 500; }
        .wrong-opt { background-color: #fff5f5; color: #c53030; border-color: #c53030; text-decoration: line-through; }
        
        .score-box { 
            background: linear-gradient(135deg, #1a73e8, #4285f4); 
            color: white; 
            padding: 20px; 
            text-align: center; 
            font-size: 1.5em; 
            font-weight: bold; 
            border-radius: 12px; 
            margin-bottom: 30px; 
            box-shadow: 0 4px 10px rgba(26, 115, 232, 0.3);
        }
        
        .btn { 
            display: block; width: 100%; padding: 16px; 
            background: #1a73e8; color: white; border: none; 
            border-radius: 8px; font-size: 1.1em; font-weight: bold; cursor: pointer; 
            transition: background 0.3s;
        }
        .btn:hover { background: #1557b0; }
        
        .ans-note { font-size: 0.9em; margin-top: 10px; color: #5f6368; padding-top: 10px; border-top: 1px dashed #eee; }
    </style>
</head>
<body>

<div class="container">
    <h1>📝 Trắc Nghiệm Kiến Thức</h1>

    <?php if ($showResult): ?>
        <div class="score-box">
            Kết quả: <?php echo $userScore; ?> / <?php echo count($quizArray); ?> câu đúng
        </div>
    <?php endif; ?>

    <form method="POST">
        <?php foreach ($quizArray as $index => $item): ?>
            <div class="quiz-item">
                <div class="question">
                    Câu <?php echo $index + 1; ?>: <?php echo htmlspecialchars($item['question']); ?>
                </div>

                <?php 
                $userSelected = isset($_POST['q' . $index]) ? $_POST['q' . $index] : [];
                ?>

                <?php foreach ($item['options'] as $key => $val): ?>
                    <?php 
                        $class = '';
                        $checked = in_array($key, $userSelected) ? 'checked' : '';
                        $icon = '';

                        if ($showResult) {
                            $isCorrect = in_array($key, $item['answer']);
                            $isSelected = in_array($key, $userSelected);

                            // Logic tô màu
                            if ($isCorrect) {
                                $class = 'correct-opt'; // Đáp án đúng (luôn hiện xanh)
                                $icon = '✅';
                            } 
                            if ($isSelected && !$isCorrect) {
                                $class = 'wrong-opt'; // Chọn sai (hiện đỏ)
                                $icon = '❌';
                            }
                        }
                    ?>
                    <label class="option-label <?php echo $class; ?>">
                        <input type="checkbox" name="q<?php echo $index; ?>[]" value="<?php echo $key; ?>" <?php echo $checked; ?>>
                        <span><strong><?php echo $key; ?>.</strong> <?php echo htmlspecialchars($val); ?> <?php echo $icon; ?></span>
                    </label>
                <?php endforeach; ?>
                
                <?php if ($showResult && !empty($item['answer'])): ?>
                     <div class="ans-note">
                        Đáp án đúng: <strong><?php echo implode(', ', $item['answer']); ?></strong>
                     </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn">
            <?php echo $showResult ? '🔄 Làm lại bài thi' : '🚀 Nộp bài'; ?>
        </button>
    </form>
</div>

</body>
</html>