<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
	    <title>{{$brandname}}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    	<style>
	        html,body {
	            height:100%;
	        }
	        .error-container{
			    width:100%;
			    height:100%;
			    background: #f0eff5;
			    background-repeat: repeat;
			    padding-top: 100px;
			}
			.error-content{
			    background:#fff;
			    box-shadow: 0 0 5px 0 rgba(0,0,0,.1); 
			    border-radius: 10px;
			    width:80%;
			    margin:0 auto;
			    text-align: center;
			    padding:40px 20px;
			}
			.error-content img{
			    width:200px;
			}
			.error-text{
			    font-size: 18px;
			    color: #8c8c8c;
			    font-weight: bold;
			    margin-bottom: 10px;
			}

	    </style>
	</head>
	<body>
		<div class='error-container'>	
			<div class='error-content'>
				<img src="http://open.weixin.qq.com/qr/code/?username={{$account}}">
				
				<div class='error-text'>进入品牌公众号才可以买买买哟~</div>
				<div class='error-text'>微信长按二维码关注</div>
			</div>
		</div>
	</body>
</html>