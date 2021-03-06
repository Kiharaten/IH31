<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- reset css -->
    <link rel="stylesheet" type="text/css" href="./css/destyle.css">

    <!-- header css -->
    <link rel="stylesheet" type="text/css" href="./css/adHeader.css">

    <!-- main css -->
    <link rel="stylesheet" type="text/css" href="./css/receipt.css">

    <!-- footer css -->
    <link rel="stylesheet" type="text/css" href="./css/footer.css">
    <title>領収書</title>
</head>

<body>
    <div id="wrapper">
        <h1 class="big center mgn_top mgn_bottom">請求書</h1>

        <div class="flex">
            <p class="left middle"><span class="border_bottom"><?= $_GET["client_name"] ?></span> 御中</p>
            <div class="right">
                <p class="middle">No. 121<br>
                    発行日 2021/01/26</p>
            </div>
        </div>



        <p class="center middle mgn">下記の通り引き落としを行います</p>

        <div id="tables" class="flex">
            <table class="table1">
                <tr class="border_bottom">
                    <td>内訳</td>
                    <td></td>
                </tr>
                <tr>
                    <td>車両金額</td>
                    <td>¥<?= $_GET["money"] ?>0,000 -</td>

                </tr>
                <tr>
                    <td>手数料</td>
                    <td>¥10,000 -</td>
                </tr>
                <tr>
                    <td>運送料</td>
                    <td>¥10,000 -</td>
                </tr>
                <tr>
                    <td>合計</td>
                    <td>¥<?= $_GET["money"] + 2 ?>0,000 -</td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>住所</td>
                    <td>〒530-0013<br>
                        大阪府大阪市北区1-1<br>
                        HALMORTOR
                    <td>
                </tr>
                <tr>
                    <td>TEL</td>
                    <td>06-7737-8738</td>
                </tr>
            </table>


        </div>
        <dl>
            <dt class="middle border_bottom">引落先</dt>
            <dd>大阪銀行　道頓堀支店<br>
                (普)1239494043
            </dd><br>

            <dt class="middle border_bottom">引落日</dt>
            <dd>2021/02/03</dd>
        </dl>
        <hr>

        <p class="frame">※A4用紙縦向きで印刷し、下部の余白部分は切り取って使用ください。</p>

    </div>





</body>

</html>