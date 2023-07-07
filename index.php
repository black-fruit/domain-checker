<!DOCTYPE html>
<html>
<head>
    <title>域名检测</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A new start page for search, support Bing and Google!">
    <meta name="author" content="black-fruit">
    <!-- Favicon -->
    <link rel="icon" href="../../assets/img/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="../../assets/css/argon.css?v=1.2.1" type="text/css">
    <style>
        .container {
            text-align: center;
            margin-top: 50px;
        }
        #result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <pre>
            
        </pre>
        <h1>域名可用性检测</h1>
        <pre>
            
        </pre>
        <form action="" method="post">
            <div class="input-group">
                <input type="text" class="form-control w-100" name="domain" placeholder="请输入域名">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-success">检查</button>
                </div>
            </div>
        </form>
        <pre>


        </pre>
        <div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#resultModal">
              查看结果
            </button>
            <!-- Modal -->
            <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resultModalLabel">检测结果</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="resultContent"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                            <a id="purchaseButton" class="btn btn-success" href="#" target="_blank" style="display: none;">购买域名</a>
                            <a id="whoisButton" class="btn btn-success" href="#" target="_blank" style="display: none;">查询 Whois</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <?php
    if (isset($_POST['domain'])) {
        $api_key = "7M7P8E9R6L8p7i8z37P9I7o8y617d6S6Z6e6w8s7Q8l8s8Y";
        $command = "search";
        $domain = $_POST['domain'];
        $url = "https://api.dynadot.com/api3.json?key=$api_key&command=$command&domain0=$domain";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($output, true);
        $response_code = $result["SearchResponse"]["ResponseCode"];

        if ($response_code != "0") {
            echo "<script>
                $(document).ready(function() {
                    $('#resultContent').html('<p class=\"text-danger\">Error: " . $result["SearchResponse"]["ResponseCodeList"][$response_code] . "</p>');
                });
            </script>";
        } else {
            echo "<script>
                $(document).ready(function() {
                    var resultHtml = '';
                    var searchResults = " . json_encode($result["SearchResponse"]["SearchResults"]) . ";
                    searchResults.forEach(function(searchResult) {
                        resultHtml += '<p>域名: ' + searchResult['DomainName'] + '</p>';
                        resultHtml += '<p>检测状态: ' + searchResult['Status'] + '</p>';
                        resultHtml += '<p>可用性: ' + searchResult['Available'] + '</p><br>';
                        
                        if (searchResult['Available'] === 'yes') {
                            $('#purchaseButton').show();
                            $('#purchaseButton').attr('href', 'https://www.dynadot.com/domain/');
                            resultHtml += '<p>注册Dynadot时，务必使用邀请码 K6J8Su6g8jC8G</p>';
                        } else {
                            $('#whoisButton').show();
                            $('#whoisButton').attr('href', 'https://www.whois.com/whois/' + searchResult['DomainName']);
                        }
                    });
                    $('#resultContent').html(resultHtml);
                });
            </script>";
        }
    }
    ?>
</body>
</html>
