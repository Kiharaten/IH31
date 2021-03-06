<?php

// require_once '../config.php';
require_once '../defaultConfig.php';

// require_once '../functions/admin/reqistration.php';
// require_once '../functions/admin/detail_matter.php';
// require_once '../functions/archived/select.php';

function load_page_source() {
// 画面の固定値指定
    $const = [
        "title" => "TOP | HALMORTOR",
        "path_to_logo_image" => "./img/HALMOTOR.png",
        "regist" => "登録",
        "matter" => "案件検索",
        "change" => "変更・管理",
        "log_out" => "ログアウト",
    ];
    return $const;
}

// --------------- 検索系 --------------- //
// ---------- 登録 ---------- //
// ----- 出品登録 -----
function search_auction() {
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT * FROM auction;";
    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [];

    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

// ---------- 案件検索 ---------- //
// ----- 案件検索トップ -----
function search_matter() {
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT matter_no, status, client.client_name, employee.employee_name, vehicle_no, money 
            FROM matter
            INNER JOIN client ON matter.client_no = client.client_no 
            INNER JOIN employee ON matter.employee_no = employee.employee_no
            ORDER BY matter_no DESC;";
    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [];

    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

// ----- 案件詳細検索 -----
function search_matter_detail($id) {

    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT * , client.client_name, employee.employee_name
    FROM matter
    INNER JOIN vehicle ON vehicle.vehicle_no = matter.vehicle_no
    INNER JOIN client ON client.client_no = matter.client_no
    INNER JOIN employee ON employee.employee_no = matter.employee_no
    WHERE matter_no = " . $id . ";";
    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [];
    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

// ----- 従業員検索 -----
function search_employee(){
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT * FROM employee";
    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [];
    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

// ---------- 変更・管理 ---------- //
function change_auction() {
    $date = date("Y/m/d ");
    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT a.auction_no,a.auction_name,a.explanation,a.date as 'auction_date' ,COUNT(*) as 'auction_car_count'  FROM auction a 
LEFT OUTER JOIN listing l ON a.auction_no = l.auction_no GROUP BY a.auction_no";

    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [];
    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

function change_auction_detail($id) {
    //追加で車の情報などを表示するならSELECTにカラムを追加してください
    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "SELECT l.listing_no,l.vehicle_no,lx.client_no,client.client_name,lh.money FROM listing l
    LEFT OUTER JOIN (SELECT listing_no,MAX(money)as 'money' FROM listing_history GROUP BY listing_no) lh ON l.listing_no = lh.listing_no
    LEFT OUTER JOIN listing_history lx  ON lh.money = lx.money
    LEFT OUTER JOIN client ON lx.client_no = client.client_no
    WHERE l.auction_no = " . $id . ";";
    $sql_result = mysqli_query($cn, $sql);
    mysqli_close($cn);
    $result = [];
    while ($row = mysqli_fetch_assoc($sql_result)) {
        $result[] = $row;
    }
    return $result;
}

// --------------- 登録系 --------------- //
// 従業員登録
function regist_employee() {
    $id = $_POST["employee_id"];
    $name = $_POST["employee_name"];
    $passwd = $_POST["employee_passwd"];

    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "INSERT INTO employee(employee_no,employee_name,password)VALUES(" . $id . ",'" . $name . "','" . $passwd . "');";
    mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [
        $id,
        $name,
        $passwd,
    ];
    return $result;
}

// 車両登録
function regist_car() {
    $car_no = $_POST["car_no"];
    $model_year = $_POST["model_year"];
    $car_type = $_POST["car_type"];
    $car_form = $_POST["car_form"];
    $car_displacement = $_POST["car_displacement"];
    $car_model = $_POST["car_model"];
    $car_grade = $_POST["car_grade"];
    $car_maker = $_POST["car_maker"];
    $car_fuel = $_POST["car_fuel"];
    $car_format = $_POST["car_format"];
    $car_capacity = $_POST["car_capacity"];
    $car_system = $_POST["car_system"];
    $car_mileage = $_POST["car_mileage"];
    $car_outer_color = $_POST["car_outer_color"];
    $car_inner_color = $_POST["car_inner_color"];
    $car_nox_limit = $_POST["car_nox_limit"];
    $car_check_limit = $_POST["car_check_limit"];
    $car_namechange_limit = $_POST["car_namechange_limit"];
    $car_airbag = $_POST["car_airbag"];
    $car_history = $_POST["car_history"];
    $car_type_no = $_POST["car_type_no"];
    $car_repair_history = $_POST["car_repair_history"];
    $car_evaluation_point = $_POST["car_evaluation_point"];
    $car_outer_point = $_POST["car_outer_point"];
    $car_inner_point = $_POST["car_inner_point"];
    $car_wholesale = $_POST["car_wholesale"];
    $car_status_comment = $_POST["car_status_comment"];
    $car_appeal_comment = $_POST["car_appeal_comment"];

    // 画像取得
    $images = [
        $_FILES["image1"],
        $_FILES["image2"],
        $_FILES["image3"],
        $_FILES["image4"],
    ];

    // 別名で保存
    for ($i = 0; $i < 4; $i++):
        // 画像保存先パスの生成・保存
        $image_path = "../pro3api/public/car_img/" . $car_no . "_" . $i . ".jpg";
        move_uploaded_file($images[$i]["tmp_name"], $image_path);
    endfor;
    // 画像名は、
    // 車体NO + _0.jpg
    // 車体NO + _1.jpg
    // 車体NO + _2.jpg
    // 車体NO + _3.jpg
    // になっているので、呼び出す画面でループさせます。
    // 車体NOだけわかれば表示できるので、画像パスはDB保存しなくて大丈夫です。
    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "INSERT INTO vehicle(vehicle_no,model_year,model_name,shape,displacement,model,grade,maker,fuel,vehicle_model,capacity,drive_system,mileage,exterior_color,Interior_color,nox_deadline,Inspection_deadline,name_change_deadline,airbag,car_history,classification_classification_number,repair_history,evaluation_points,exterior_evaluation,interior_evaluation,purchase_amount,situation_comment,appeal_point)
    VALUES(" . $car_no . ",'" . $model_year . "','" . $car_type . "','" . $car_form . "','" . $car_displacement . "','" . $car_model . "','" . $car_grade . "','" . $car_maker . "','" . $car_fuel . "','" . $car_format . "','" . $car_capacity . "','" . $car_system . "','" . $car_mileage . "','" . $car_outer_color . "','" . $car_inner_color . "','" . $car_nox_limit . "','" . $car_check_limit . "','" . $car_namechange_limit . "',
    '" . $car_airbag . "','" . $car_history . "','" . $car_type_no . "','" . $car_repair_history . "','" . $car_evaluation_point . "','" . $car_outer_point . "','" . $car_inner_point . "','" . $car_wholesale . "','" . $car_status_comment . "','" . $car_appeal_comment . "');";
    mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [
        "<p><img src='../pro3api/public/car_img/" . $car_no . "_0.jpg' height='250', width='250'></p>",
        "<p><img src='../pro3api/public/car_img/" . $car_no . "_1.jpg' height='250', width='250'></p>",
        "<p><img src='../pro3api/public/car_img/" . $car_no . "_2.jpg' height='250', width='250'></p>",
        "<p><img src='../pro3api/public/car_img/" . $car_no . "_3.jpg' height='250', width='250'></p>",
        $car_no,
        $model_year,
        $car_type,
        $car_form,
        $car_displacement,
        $car_model,
        $car_grade,
        $car_maker,
        $car_fuel,
        $car_format,
        $car_capacity,
        $car_system,
        $car_mileage,
        $car_outer_color,
        $car_inner_color,
        $car_nox_limit,
        $car_check_limit,
        $car_namechange_limit,
        $car_airbag,
        $car_history,
        $car_type_no,
        $car_repair_history,
        $car_evaluation_point,
        $car_outer_point,
        $car_inner_point,
        $car_wholesale,
        $car_status_comment,
        $car_appeal_comment,
    ];
    return $result;
}

// 出品登録
function regist_exhibit() {
    $car_id = $_POST["car_id"];
    $auction_id = $_POST["auction_id"];

    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "INSERT INTO listing(
        listing_no,
        vehicle_no,
        auction_no
    )select
        max(listing_no)+1,
        '" . $car_id . "',
        '" . $auction_id . "'
    from listing;";
    mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [
        $car_id,
        $auction_id,
    ];
    return $result;
}

// 案件登録
function regist_matter() {
    // $matter_id = $_POST["matter_id"];
    $customer_id = $_POST["customer_id"];
    $employee_id = $_POST["employee_id"];
    $car_id = $_POST["car_id"];
    $car_price = $_POST["car_price"];

    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "INSERT INTO matter(
                matter_no,
                status,
                client_no,
                employee_no,
                vehicle_no,
                money
            )select 
                max(matter_no)+1,
                '1',
                '" . $customer_id . "',
                '" . $employee_id . "',
                '" . $car_id . "',
                '" . $car_price . "'
            from matter;";

    mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [
        $customer_id,
        $employee_id,
        $car_id,
        $car_price,
    ];
    return $result;
}

// オークション登録
function regist_auction() {
    // $auction_id = $_POST["auction_id"];
    $auction_name = $_POST["auction_name"];
    $auction_date = $_POST["auction_date"];
    $auction_description = $_POST["auction_description"];

    // db接続sql実行してください。
    $cn = mysqli_connect(HOSTNAME, MYSQL_USER, MYSQL_PASS, DB_NAME);
    mysqli_set_charset($cn, 'utf8');
    $sql = "INSERT INTO auction(
                auction_no,
                date,
                explanation,
                auction_name
            )select
                max(auction_no)+1,
                '" . $auction_date . "',
                '" . $auction_description . "',
                '" . $auction_name . "'
            from auction;";
    mysqli_query($cn, $sql);
    mysqli_close($cn);

    $result = [
        $auction_id,
        $auction_name,
        $auction_date,
        $auction_description,
    ];
    return $result;
}

//トップページ　円グラフ用データ取得
function get_top_page(){
    $result = [];
    $sql = "SELECT status,COUNT(matter_no) FROM matter GROUP BY status";

    // db接続sql実行してください。
    $cn = new mysqli(HOSTNAME,MYSQL_USER,MYSQL_PASS,DB_NAME);
    if ($cn->connect_errno){
        echo("Connect ERR");
    }
    $cn -> set_charset('utf8');
    $stmt = $cn -> prepare($sql);
    $stmt -> execute();
    $stmt -> bind_result($status,$count);
    for($i = 0 ; $stmt -> fetch();$i ++){
        array_push($result,[$status,$count]);  
    }
    return $result;
}
