<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Camera Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        footer {
            text-align: center;
            padding: 16px 0;
        }

        .footer-top {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0;
            margin-bottom: 20px;
            height: 70px;
        }

        .footer-top img {
            height: 50px;
        }

        .footer-top li {
            position: relative;
            padding: 0 16px;
            list-style: none;
        }

        .footer-top li:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 22px;
            background: #ccc;
        }

        /* Link chữ */
        .footer-top a {
            text-decoration: none;
            color: #000;
            font-weight: 600;
        }

        .footer-top>li a {
            font-size: 16px;
        }

        .footer-top .social a {
            margin: 0 6px;
        }

        .footer-top .social i {
            font-size: 18px;
        }


        .footer-bottom {
            border-top: 1px solid #eee;
            padding: 12px 0 0;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>