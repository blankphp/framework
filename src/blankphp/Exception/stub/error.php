<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <title><?php $this->getValue($this->_message); ?></title>


    <style>
        body {
            background-color: #2F3242;
        }

        svg {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -250px;
            margin-left: -400px;
        }

        .message-box {
            height: 600px;
            width: 400px;
            position: absolute;
            top: 30%;
            left: 10%;
            margin-top: -100px;
            margin-left: 50px;
            color: #FFF;
            font-family: Roboto;
            font-weight: 300;
        }

        .message-box h1 {
            font-size: 50px;
            line-height: 60px;
            margin-bottom: 40px;
        }

        .buttons-con .action-link-wrap {
            margin-top: 40px;
        }

        .buttons-con .action-link-wrap a {
            background: #68c950;
            padding: 8px 25px;
            border-radius: 4px;
            color: #FFF;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s linear;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px
        }

        .buttons-con .action-link-wrap a:hover {
            background: #5A5C6C;
            color: #fff;
        }

    </style>


</head>

<body>
<div class="message-box">
    <h1><?php $this->getValue($this->_message); ?></h1>
    <p><?php $this->getValue($this->_file); ?>:<?php $this->getValue($this->_line); ?></p>
    <div class="buttons-con">
        <?php $this->getValue($this->_trace); ?>
    </div>
</div>


</body>

</html>
