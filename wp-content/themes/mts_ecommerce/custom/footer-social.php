<style>
    .footer-social {
        background-color: white;
        clear: both;
        float: left;
        width: 100%;
        border-bottom: 1px solid rgba(112,112,112,.14);
        border-top: 1px solid rgba(112,112,112,.14);
        padding: 10px 0;
    }
    .footer-social .container {
        padding: 30px 0 20px 0;
        width: 90%!important;
        margin: 0 auto;
        max-width: 100%;
    }
    .footer-social ul.logos {
        margin: 0;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxIDgyLjA0MiI+CiAgPGRlZnM+CiAgICA8c3R5bGU+CiAgICAgIC5jbHMtMSB7CiAgICAgICAgZmlsbDogbm9uZTsKICAgICAgICBzdHJva2U6ICM3MDcwNzA7CiAgICAgICAgb3BhY2l0eTogMC4xNDsKICAgICAgfQogICAgPC9zdHlsZT4KICA8L2RlZnM+CiAgPHBhdGggaWQ9IlBhdGhfNTA0IiBkYXRhLW5hbWU9IlBhdGggNTA0IiBjbGFzcz0iY2xzLTEiIGQ9Ik0zOTgwLDQyOS4wNzNoODIuMDQyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg0MjkuNTczIC0zOTgwKSByb3RhdGUoOTApIi8+Cjwvc3ZnPgo=);
        background-repeat: no-repeat;
        background-position: 90% 0;
        padding: 12.5px 30px 7.5px 0;
        margin-right: 2.5%;
        width: 30%;
    }
    .footer-social ul li.label-social {
        text-transform: uppercase;
        padding-right: 25px;
        font-family: 'Roboto Condensed',sans-serif;
        font-weight: 600;
        color: #575D5E;
        vertical-align: middle;
        line-height: 28px;
    }
    .footer-social ul li {
        display: inline-block;
        color: #fff;
    }
    .logos li {
        margin-bottom: 5px;
    }
    .logos li {
        float: left;
    }
    .footer-social ul li a.social-fb {
        display: inline-block;
        height: 25px;
        width: 25px;
        background: url(data:image/svg+xml;base64,PHN2ZyBpZD0iR3JvdXBfMTEyIiBkYXRhLW5hbWU9Ikdyb3VwIDExMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMzMuMTc4IDM0LjQ3OSI+CiAgPGRlZnM+CiAgICA8c3R5bGU+CiAgICAgIC5jbHMtMSB7CiAgICAgICAgZmlsbDogIzQ4NDg0ODsKICAgICAgfQoKICAgICAgLmNscy0yIHsKICAgICAgICBmaWxsOiAjZWZlYmUzOwogICAgICB9CiAgICA8L3N0eWxlPgogIDwvZGVmcz4KICA8cmVjdCBpZD0iUmVjdGFuZ2xlXzExNSIgZGF0YS1uYW1lPSJSZWN0YW5nbGUgMTE1IiBjbGFzcz0iY2xzLTEiIHdpZHRoPSIzMy4xNzgiIGhlaWdodD0iMzQuNDc5IiByeD0iMyIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCkiLz4KICA8ZyBpZD0iQXNzZXRfMWZhY2Vib29rIiBkYXRhLW5hbWU9IkFzc2V0IDFmYWNlYm9vayIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTEuNzEgNy4xNTYpIj4KICAgIDxnIGlkPSJPYmplY3RzIj4KICAgICAgPHBhdGggaWQ9IlBhdGhfNDE0IiBkYXRhLW5hbWU9IlBhdGggNDE0IiBjbGFzcz0iY2xzLTIiIGQ9Ik0yLjA3NSwxOS44MTloMy45OVY5LjgyNUg4Ljg1MWwuMy0zLjMyOUg2LjA2NVY0LjU4NmMwLS43OTEuMTYxLTEuMS45MjEtMS4xSDkuMTQ5VjBINi4zODZDMy40MTMsMCwyLjA3NSwxLjMwNywyLjA3NSwzLjgyMlY2LjVIMHYzLjM5SDIuMDc1WiIvPgogICAgPC9nPgogIDwvZz4KPC9zdmc+Cg==);
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-right: 20px;
        margin-top: 0;
    }
    .footer-social ul li a.social-yt {
        display: inline-block;
        height: 25px;
        width: 25px;
        background: url(data:image/svg+xml;base64,PHN2ZyBpZD0iR3JvdXBfMTE0IiBkYXRhLW5hbWU9Ikdyb3VwIDExNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMzMuMTc4IDM0LjQ3OSI+CiAgPGRlZnM+CiAgICA8c3R5bGU+CiAgICAgIC5jbHMtMSB7CiAgICAgICAgZmlsbDogIzQ4NDg0ODsKICAgICAgfQoKICAgICAgLmNscy0yIHsKICAgICAgICBmaWxsOiAjZWZlYmUzOwogICAgICB9CiAgICA8L3N0eWxlPgogIDwvZGVmcz4KICA8cmVjdCBpZD0iUmVjdGFuZ2xlXzExMiIgZGF0YS1uYW1lPSJSZWN0YW5nbGUgMTEyIiBjbGFzcz0iY2xzLTEiIHdpZHRoPSIzMy4xNzgiIGhlaWdodD0iMzQuNDc5IiByeD0iMyIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCkiLz4KICA8ZyBpZD0iQXNzZXRfNHlvdXR1YmUiIGRhdGEtbmFtZT0iQXNzZXQgNHlvdXR1YmUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDcuODA3IDcuMTU2KSI+CiAgICA8ZyBpZD0iT2JqZWN0cyI+CiAgICAgIDxwYXRoIGlkPSJQYXRoXzQxOSIgZGF0YS1uYW1lPSJQYXRoIDQxOSIgY2xhc3M9ImNscy0yIiBkPSJNMjcuNTExLDEwLjkxNGExLjY2NCwxLjY2NCwwLDAsMCwxLjIxNS0uNzA4di42MjdoMS4wNDlWNS4xODRIMjguNzI2djQuMjhjLS4xMjguMTYzLS40MTQuNDIyLS42MTkuNDIycy0uMjc5LS4xNTEtLjI3OS0uMzg3VjUuMThIMjYuNzhWOS45QzI2Ljc4LDEwLjQ1NCwyNi45NSwxMC45MTQsMjcuNTExLDEwLjkxNFoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xNi40MTggLTMuMTc2KSIvPgogICAgICA8cGF0aCBpZD0iUGF0aF80MjAiIGRhdGEtbmFtZT0iUGF0aCA0MjAiIGNsYXNzPSJjbHMtMiIgZD0iTTE2LjYwNyw5LjM1NWExLjM1NywxLjM1NywwLDAsMCwxLjU0OCwxLjUwOSwxLjQzNiwxLjQzNiwwLDAsMCwxLjUwOS0xLjUwOVY2LjZhMS40NywxLjQ3LDAsMCwwLTEuNTA5LTEuNTEzQTEuNDM5LDEuNDM5LDAsMCwwLDE2LjYwNyw2LjZabTEuMDgtMi42NzRjMC0uMzEuMTQzLS41MzguNDM3LS41MzhzLjQ2LjIyNC40Ni41MzhWOS4zYzAsLjMwNi0uMTU5LjUzLS40NDEuNTNhLjQ3Mi40NzIsMCwwLDEtLjQ1Ny0uNTNaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTAuMTggLTMuMTE0KSIvPgogICAgICA8cGF0aCBpZD0iUGF0aF80MjEiIGRhdGEtbmFtZT0iUGF0aCA0MjEiIGNsYXNzPSJjbHMtMiIgZD0iTTcuOTE1LDcuNjVIOS4xdi0zLjFMMTAuNDcyLDBoLTEuMkw4LjUxNCwzLjA1Nyw3LjcsMEg2LjUxbDEuNCw0LjU0N1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0zLjk5MSkiLz4KICAgICAgPHBhdGggaWQ9IlBhdGhfNDIyIiBkYXRhLW5hbWU9IlBhdGggNDIyIiBjbGFzcz0iY2xzLTIiIGQ9Ik0xNC43NjksMjMuMDhIMi4zODdBMi40MDcsMi40MDcsMCwwLDAsMCwyNS40ODd2Ni4wNzVhMi40MDcsMi40MDcsMCwwLDAsMi40LDIuNDA3SDE0Ljc4NWEyLjQwNywyLjQwNywwLDAsMCwyLjM4Ny0yLjQwN1YyNS40ODdhMi40MDcsMi40MDcsMCwwLDAtMi40LTIuNDA3Wk00LjA1MSwzMi4xNjVIMi45MjlWMjUuOTM5SDEuNzY4VjI0Ljg4M0g1LjIydjEuMDU2SDQuMDU5Wm00LDBoLTF2LS42YTIuMSwyLjEsMCwwLDEtLjU3My41Yy0uNTM4LjMxLTEuMjc3LjMtMS4yNzctLjc3NFYyNi43OTVoLjk4N3Y0LjExN2MwLC4yMTcuMDUuMzY0LjI2My4zNjRzLjQ2OC0uMjUyLjU4OC0uMzg3VjI2Ljc5NWgxWk0xMS45LDMxLjA1NWMwLC42NjItLjI0OCwxLjE4LS45MTMsMS4xOGExLjE2MSwxLjE2MSwwLDAsMS0uOTQ4LS40OHYuNDFIOS4wMjdWMjQuODgzaDEuMDF2Mi4zNDVhMS4yLDEuMiwwLDAsMSwuODg2LS41Yy43MzEsMCwuOTc1LjYxOS45NzUsMS4zNDdabTMuNjkxLTEuNDI4SDEzLjY4MnYxLjAxNGMwLC4zODcuMDMxLjc1MS40MzMuNzUxcy40NDktLjI4Ni40NDktLjc1MXYtLjM4N0gxNS41OXYuMzg3YzAsMS4wMzMtLjQ0NSwxLjY2LTEuNSwxLjY2LS45NTIsMC0xLjQzOS0uNy0xLjQzOS0xLjY2di0yLjRhMS40NzQsMS40NzQsMCwwLDEsMS41MTctMS41NzljLjk2LDAsMS40Mi42MDcsMS40MiwxLjU3OVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTE0LjE0OSkiLz4KICAgICAgPHBhdGggaWQ9IlBhdGhfNDIzIiBkYXRhLW5hbWU9IlBhdGggNDIzIiBjbGFzcz0iY2xzLTIiIGQ9Ik0zNS44LDM0Ljc2Yy0uMzg3LDAtLjQ0NS4yNTktLjQ0NS42MzF2LjU0MmguODgydi0uNTQyQzM2LjI0MiwzNS4wMjcsMzYuMTY1LDM0Ljc2LDM1LjgsMzQuNzZaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjEuNjc4IC0yMS4zMSkiLz4KICAgICAgPHBhdGggaWQ9IlBhdGhfNDI0IiBkYXRhLW5hbWU9IlBhdGggNDI0IiBjbGFzcz0iY2xzLTIiIGQ9Ik0yNi4xNDUsMzQuNzU1YS43NzQuNzc0LDAsMCwwLS4yMDUuMTY2djMuMzQ3YS44MzIuODMyLDAsMCwwLC4yMzYuMTkuNDI2LjQyNiwwLDAsMCwuNTExLS4wNTQuNjI3LjYyNywwLDAsMCwuMDg1LS4zODdWMzUuMjU4YS42ODkuNjg5LDAsMCwwLS4xLS40MTRBLjQyMi40MjIsMCwwLDAsMjYuMTQ1LDM0Ljc1NVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xNS45MDMgLTIxLjI3NCkiLz4KICAgIDwvZz4KICA8L2c+Cjwvc3ZnPgo=);
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-right: 20px;
    }
    .footer-social ul.cards_ix {
        padding: 7.5px 0 7.5px 0;
        width: 50%;
    }
    .footer-social ul li.label-social span {
        font-weight: 400;
    }
    .footer-social ul li a.credit_card {
        display: inline-block;
        height: 55px;
        width: 65px;
        background-size: 65px!important;
        vertical-align: middle;
        background: url(data:image/png;base64,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);
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-right: 20px;
    }
    .footer-social ul li a.debit_card {
        display: inline-block;
        height: 55px;
        width: 65px;
        background-size: 65px!important;
        vertical-align: middle;
        background: url(data:image/png;base64,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);
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-right: 20px;
    }
    .footer-social ul li a.net_banking {
        display: inline-block;
        height: 55px;
        width: 65px;
        background-size: 65px!important;
        vertical-align: middle;
        background: url('https://www.decathlon.vn/content/rabbit.png');
        background-repeat: no-repeat;
        background-position: 0 0;
        margin-right: 20px;
    }
    .footer-social ul {
        float: left;
        vertical-align: middle;
        padding-left: 0;
    }
    @media screen and (max-width: 1440px) and (min-width: 1280px)
        .footer-social ul.cards_ix {
            width: 52%;
        }
</style>
<div class="footer-social">
    <div class="container">
        <ul class="logos">
            <li class="label-social"><p>Kết nối với <span>chúng tôi</span></p></li>
            <li><a href="https://www.facebook.com/DecathlonVN/" class="social-fb"> </a></li>
            <li><a href="https://www.youtube.com/channel/UCKGx2KMsrGggPbPQDNkYUIg/featured" class="social-yt"> </a></li>
            <li><a href="https://zalo.me/" class="zalo"> </a></li>
        </ul>
        <ul id="hidden-mobile" class="cards_ix">
            <li class="label-social"><p>Hình thức <span>thanh toán</span></p></li>
            <li><a class="credit_card" target="_blank"> </a></li>
            <li><a class="debit_card" target="_blank"> </a></li>
            <li><a class="net_banking" target="_blank"> </a></li>
            <li><a href="https://zalopay.vn/" class="upi" target="_blank"> </a></li>
        </ul>
    </div>
</div>