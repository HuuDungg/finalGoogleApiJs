<?php include './index.php' ?>
<?php
// Nhận dữ liệu từ form
$jsonD = $_POST['data'];

// Giải mã JSON
$data = json_decode($jsonD, true);

// Kiểm tra lỗi giải mã
if (json_last_error() !== JSON_ERROR_NONE) {
    exit;
}


function transformData($data) {
    $tasks = [];
    $taskIndex = 0;
    $detailIndex = 0;

    foreach ($data as $item) {
        if (count($item) === 1) {
            // Đây là số nhiệm vụ
            $taskIndex++;
            $detailIndex = 0;
            $tasks[$taskIndex] = [
                'no' => $item[0],
                'task' => '',
                'time' => 0,
                'details' => [],
            ];
            continue; // Bỏ qua vòng lặp hiện tại và tiếp tục với item tiếp theo
        }

        // Đây là nhiệm vụ hoặc chi tiết
        if (count($item) >= 5) {
            list($code, $taskName, $desc, , $time) = $item;

            if ($taskName) {
                // Đây là nhiệm vụ
                $tasks[$taskIndex]['task'] = $taskName;
                $tasks[$taskIndex]['time'] += (float)$time;
                continue; // Bỏ qua vòng lặp hiện tại và tiếp tục với item tiếp theo
            }

            // Đây là chi tiết
            $tasks[$taskIndex]['details'][$detailIndex] = [
                'time' => (float)$time,
                'desc' => $desc,
                'class' => 'detail-' . $detailIndex,
            ];
            $detailIndex++;
        }
    }

    return $tasks;
}
$tasks = transformData($data);
$total_time = 0;
foreach ($tasks as $task) {
    foreach ($task['details'] as $detail) {
        $total_time += $detail['time'];
    }
}
// Xác định số ngày tối đa
$max_days = ceil($total_time / 8);
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/traingoogleapi/css/style.css">

    </style>
</head>
<body>


    
<table class="timeline">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Task</th>
            <th rowspan="2">Estimated time (hours)</th>
            <th colspan="<?php echo $max_days ?>">Day</th>
        </tr>
        <tr>
            <?php for ($day = 1; $day <= $max_days; $day++): ?>
                <th><?= $day ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_time = 0; // Tổng thời gian của tất cả chi tiết
    $tmp_time = 0; // Tổng thời gian tạm thời cho các chi tiết trong một hàng
    ?>
    <?php foreach ($tasks as $task): ?>
        <?php foreach ($task['details'] as $index => $detail): ?>
            <?php
            $total_time += $detail['time']; // Cộng dồn thời gian chi tiết vào tổng thời gian
            $td_number = ceil($total_time / 8); // Xác định ngày dựa trên tổng thời gian

            $tmp_time += $detail['time']; // Cộng dồn thời gian chi tiết vào thời gian tạm thời
            $colspan = ceil($tmp_time / 8); // Xác định kích thước cột dựa trên thời gian tạm thời
            if ($colspan > 1) {
                $colspan = 2; // Nếu kích thước cột lớn hơn 1, đặt lại là 2 (giới hạn tối đa)
                $td_number = $td_number - 1; // Điều chỉnh lại số ngày
                $tmp_time = fmod($tmp_time, 8); // Lấy phần dư của thời gian tạm thời cho lần tính toán tiếp theo
            }

            // Số lượng ô trống cần thiết để điền cho ngày sau khi dữ liệu chi tiết đã hết
            $total_days = $max_days;
            $current_day = $td_number + $colspan;
            $empty_cells = $total_days - $current_day;
            ?>

            <tr>
                <?php if ($index === 0): ?>
                    <td rowspan="<?= count($task['details']) ?>"><?= $task['no'] ?></td>
                    <td rowspan="<?= count($task['details']) ?>"><?= $task['task'] ?></td>
                <?php endif; ?>

                <!-- Hiển thị thời gian của từng chi tiết trong ô riêng biệt -->
                <td><?= $detail['time'] ?></td>

                <!-- Các ô trống trước ô dữ liệu -->
                <?php for ($i = 1; $i < $td_number; $i++): ?>
                    <td class="empty"></td>
                <?php endfor; ?>

                <!-- Ô dữ liệu -->
                <td style="background-color: #<?php echo substr(md5(rand()), 0, 6); ?>" class="<?= $detail['class'] ?>" colspan="<?php echo $colspan; ?>">
                    <?= $detail['desc'] ?>
                </td>

                <!-- Các ô trống sau ô dữ liệu -->
                <?php for ($i = 0; $i <= $empty_cells; $i++): ?>
                    <td class="empty"></td>
                <?php endfor; ?>

            </tr>

        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>

    <script src="/traingoogleapi/js/main.js"></script>
</body>
</body>
</html>

