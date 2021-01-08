////////////////////////////////////////////////////////////////////////////
//オークションＡＰＩ
//使用書
//http://localhost:9000/auction/:顧客id
/////////////////////////////////////////////////////////////////////////////
var startHour = 15;  //オークション開催時刻　時(13時)
var auctionTime = 1; //オークションの開催時間 　分(10)
var buffMinutues= 20;  //オークションの開催時刻　分（0）　デバック用
////////////////////////////////////////////////////////////////////////////
const express = require('express');
const app = express();
const http_socket = require('http').Server(app);
const io_socket = require('socket.io')(http_socket);

app.set('view engine', 'ejs');

var bodyParser = require('body-parser');
app.use(bodyParser.urlencoded({extended: true}));

/**
 * DB 定数
 */
const mysql = require("mysql");
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'vehicle_auction_site_db'
})
//計算用
var date = new Date();  //タイマー
var endDate = new Date();   //終了時刻
var difference = new Date();  //残り時間

//表示用
var strDate = "";  //タイマー  hh:mm:ss
var strEndDate = ""; //終了時刻 hh:mm:ss
var strDifference = "";//残り時間 hh:mm:ss

var aucCount = -1; //オークションカウンター
var putId = 0;  //現在の出品ＩＤ
var nowMoney = 100; //現在の金額

var page = 'auction.ejs'; //正常時のオークションページ
var errPage = 'err.ejs';  //異常時のオークションページ
/**
 * DB接続確認
 */
connection.connect((error) => {
    if (error) {
        console.log('error connecting:' + error.stack);
        return;
    }
    console.log('db_success');
});

http_socket.listen(9000);

///////////////////////////////////////////////////////////////////
/**
 * 時間取得 毎時取得
 */

var timeFunc = function () {
    date = new Date();
    var hour = date.getHours();
    var minutes = date.getMinutes();
    var second = date.getSeconds();
    strDate = hour + ':' + minutes + ':' + second
    // console.log('現在時刻 '+date.toString());
    // console.log('現在時刻 '+strDate);
}
setInterval(timeFunc, 1000);

/////////////////////////////////////////////////////////////////

/**
 * 出品取り出し 
 */
connection.query(
        "SELECT * FROM auction",
        (error, results) => {
    if (error) {
        console.log('error connectiong' + error.stack);
        return;
    }
})

//デバック用
var put = [1, 2, 3, 4, 5, 6];
var aucEnd = 5;

/////////////////////////////////////////////////////////////////////
/**
 * 終了時刻計算　
 * @type Number
 */
var endTimeFunc = function () {

    var year = date.getFullYear();
    var month = date.getMonth();
    var day = date.getDate();
   // console.log(date.toString());
    //console.log('年'+year+'月'+month+'日'+day);
    endDate = new Date((year),( month), day, startHour, (auctionTime * (aucCount+1))+buffMinutues);
   // endDate = new Date(2020, 11, 15, 11, 41 + 1 + (aucCount * auctionTime), 0);  //デバック用　上書き
    strEndDate = endDate.getHours().toString() + ':' + endDate.getMinutes().toString() + ':' + endDate.getSeconds().toString();
    console.log('終了時刻> ' + strEndDate);
    //console.log('終了時刻 :' + endDate.toString());

    /**
     * クライアントに送信
     * @returns {undefined}
     */
    io_socket.emit('end');
}
endTimeFunc();
//////////////////////////////////////////////////////////////////////////

/**
 * 残り時間計算
 */

var difTImeFunc = function () {
    difference = endDate - date;
    //console.log('difference ' + difference)
    if (difference >= 0) {
        difference = new Date(difference);
        //時間を9時間マイナスする
        strDifference = (parseInt(difference.getHours() - 9)).toString() + ":" + difference.getMinutes() + ":" + difference.getSeconds();
        console.log('残り時間> ' + strDifference);

        /**
         * クライアントに送信
         * 
         */
        io_socket.to(put[aucCount]).emit('s2cx', strDifference);


    } else {

        if (aucCount < aucEnd) {
            aucCount++;
            nowMoney = 100;
            console.log('出品番号 :'+put[aucCount]);
            endTimeFunc();

        }else{
            io_socket.emit('end');
            console.log('オークション終了');
            page=errPage;
        }
    }
    
};
/**
 * 遅延実行
 * @returns {undefined}
 */
setTimeout(function () {
    setInterval(difTImeFunc, 1000);
}, 100);

/////////////////////////////////////////////////////////////////////////
/**
 * サイトアクセス 顧客ＩＤを付与して 
 * 車両情報も送る
 */
app.get('/auction', (req, res) => {

    res.render(page,
            {
                money: nowMoney,
                time: strDifference,
                room: put[aucCount]
            });
});
////////////////////////////////////////////////////////////////////////////
io_socket.on('connection', function (socket) {

//    socket.on('auction', function (message) {
//        console.log('オークション:' + message.msg);
//        nowMoney = message.msg;
//        socket.emit('auction_d', nowMoney);
//    })


/**
 * JOIN 処理
 */
    socket.on('c2s-join', function (msg) {
        console.log('c2s-join:' + msg.auction);
        socket.join(msg.auction);
    });

/**
 * オークションやり取り
 */
    socket.on('auction', function (msg) {
        console.log('c2s-chatメッセージ:' + msg.msg);
        nowMoney = msg.msg;
        /**
         * データベース登録
         */
        io_socket.to(put[aucCount]).emit('auction_d', msg.msg);
    })
});


///////////////////////////////////////////////////////////////////////////
