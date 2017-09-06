<?php
    session_start();

	if (  !isset($_SESSION['valid']) || !($_SESSION['valid'] === true) ){
		header('location:login.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>管理</title>
<style>
#subscribeAutoReplyTextEditor li
{
	margin-bottom: 0.6em;
}
#subscribeAutoReplyTextEditor .linkInput
{
	width: 60em;
}
#subscribeAutoReplyTextEditor .textInput
{
	width: 40em;
}
#subscribeAutoReplyTextEditor .type
{
	font-weight: bold;
}
#subscribeAutoReplyTextEditor #subscribeAutoReply_editBox
{
	display: none; z-index: 9;
	width: 40em; height: 14em; padding: 2em; box-sizing: border-box; background-color: rgba(0,0,0,0.7);
	position: fixed; top: 0; right: 0; bottom: 0; left: 0; margin: auto;
}
#subscribeAutoReply_editBox input
{
	margin: 1em;
}
.noticeText
{
	color: red;
}
.warning
{
	color: red; font-size: 1.5em; font-weight: bolder;
}

</style>
</head>
<body>

<a href="sendCsMessageByOpenID/sendCsMessageByOpenID.html" ><h2>一、根据OpenID发送客服消息</h2></a>
<a href="sendCsMessageByOrderID/sendCsMessageByOrderID.html" ><h2>二、根据订单号发送客服消息</h2></a>
<a href="sendCardByOrderID/sendCardByOrderID.html" ><h2>三、根据订单号发送卡券</h2></a>
<a href="sendCardByOpenID/sendCardByOpenID.html" ><h2>四、根据OpenID发送卡券</h2></a>
<a href="sendTemplateMessageByOpenID/TemplateMessage.html" ><h2>五、根据OpenID发送模板消息 测试</h2></a>
<section id="off_duty_autoreply">
	<h2>四、下班时段自动回复</h2>
	<p>点击切换状态 <input id="switchAutoReply" type="button" value="读取中" /></p>
	<br /><br />
	<p>
		\n为折行 <textarea>读取中</textarea>
		<input id="setOffDutyAutoreply" type="button" value="提交下班时段自动回复文本" />
	</p>
</section>


<section id="subscribeAutoReplyTextEditor">
	<h2>五、新关注自动回复编辑器</h2>
	<p class="noticeText">只支持无格式文本(包括Unicode符号文本)和链接</p>
	<ul></ul>
	<div id="subscribeAutoReply_editBox">
		<input type="button" class="deleteThisLine" value="删除该行" />
		<br />
		<input type="button" class="insertNewlineBefore" value="上插入换行" />
		<input type="button" class="insertLinkBefore" value="上插入链接" />
		<input type="button" class="insertTextBefore" value="上插入文本" />
		<br />
		<input type="button" class="insertNewlineAfter" value="下插入换行" />
		<input type="button" class="insertLinkAfter" value="下插入链接" />
		<input type="button" class="insertTextAfter" value="下插入文本" />
	</div>
	<input type="button" class="copyPrevious" value="备份原文件" />
	<input type="button" class="submitAutoReplyText" value="提交修改" />
</section>

</body>
<script>

/*
 * TODO
 * 1.
 *
 *
 */

//
(function()
{

	var oOffDutyReplyEditor = document.querySelector("#off_duty_autoreply"),
		oSwitchAutoReply = oOffDutyReplyEditor.querySelector("#switchAutoReply");

	getAutoReplyByTimeState(); // 读取当前状态并写入按钮
	function getAutoReplyByTimeState()
	{
		var sUrl = "switchAutoReply/autoReplyState.json",
			fnSuccess = function( responseText )
			{
				var oManageConfiguration = JSON.parse( responseText ),
				sAutoReplyByTimeState = oManageConfiguration.autoReplyByTime;
				if( 'on' === sAutoReplyByTimeState )
				{
					oSwitchAutoReply.value = responseText = '下班时段自动回复已打开';
				}
				else
				{
					oSwitchAutoReply.value = responseText = '下班时段自动回复已关闭';
				}
			}
		AjaxGet(sUrl, fnSuccess);
	}

	var textarea = oOffDutyReplyEditor.querySelector("textarea");
	getOffDutyAutoreply();
	function getOffDutyAutoreply(){
		var sUrl = "offDutyAutoreplyText.json",
			fnSuccess = function( responseText )
			{
				textarea.value = responseText;
			},
			fnFail = function()
			{
				alert( "获取下班时段自动回复文本失败" );
			};
		AjaxGet(sUrl, fnSuccess, fnFail);
	}

	oOffDutyReplyEditor.querySelector("#setOffDutyAutoreply").addEventListener("click", function()
	{
		var sUrl = "setOffDutyAutoreply.php",
			sData = "off_duty_auto_reply_text=" + textarea.value,
			fnSuccess = function( responseText )
			{
				alert( "设置下班时段自动回复文本  成功" );
			},
			fnFail = function()
			{
				alert( "设置下班时段自动回复文本  失败" );
			};
		AjaxPost(sUrl, sData, fnSuccess, fnFail);
	});

	oSwitchAutoReply.addEventListener("click", function() // 点击按钮
	{
		var sUrl = "switchAutoReply/switchAutoReply.php",
			fnSuccess = function( responseText )
			{
				oSwitchAutoReply.value = responseText;
			},
			fnFail = function()
			{
				alert( "切换失败" );
			};
		AjaxGet(sUrl, fnSuccess, fnFail);
	}, false);

})();


// 新关注自动回复编辑器
// TODO  用户输入格式化和过滤
{
	let oSubscribeAutoReplyTextEditor = document.querySelector("#subscribeAutoReplyTextEditor"),
		oEditorUl = oSubscribeAutoReplyTextEditor.querySelector("ul");

	let aSubscribeAutoReplyEditButton = []; // XXX 这个变量定义的比较全局

	// 读取现在的自动回复内容
	AjaxGet("JSONData/subscribeAutoPlayText.json", function(responseText)
	{
		let aSubscribeAutoReplyText = JSON.parse( responseText ),
			sEditorUlHtml = "";
		aSubscribeAutoReplyText.forEach(function(item)
		{
			if( item.indexOf("<a") === 0 ) // 链接
			{
				let linkTextPattern = /'>(.+)<\//;
				let linkHrefPattern = /<a href='(.+)'>/;
				sEditorUlHtml += "<li class='subscribeAutoReply_link'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <span class='type'>链接文本：</span><input class='linkTextInput' type='text' value='" + item.match(linkTextPattern)[1] + "' /> 链接地址：<input class='linkInput' type='text' value='" + item.match(linkHrefPattern)[1] + "' /></li>";
			}
			else if( "\n" === item ) // 换行
			{
				sEditorUlHtml += "<li class='subscribeAutoReply_newline'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <input type='text' disabled='disabled' value='换行' /></li>";
			}
			else // 文本
			{
				sEditorUlHtml += "<li class='subscribeAutoReply_text'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <span class='type'>文本：</span><input class='textInput' type='text' value=" + item + " /></li>";
			}
		});

		oEditorUl.innerHTML = sEditorUlHtml;

		lineEditAddEventHandler();

		aSubscribeAutoReplyEditButton = Array.from( oEditorUl.querySelectorAll(".subscribeAutoReply_edit") );
		editHandler();
	});



	// 行编辑按钮事件绑定
	function lineEditAddEventHandler()
	{
		let aSubscribeAutoReplyEditButton = Array.from( oEditorUl.querySelectorAll(".subscribeAutoReply_edit") ),
			oEditBox = oSubscribeAutoReplyTextEditor.querySelector("#subscribeAutoReply_editBox");
		aSubscribeAutoReplyEditButton.forEach(function(item, index)
		{
			item.index = index;
			item.addEventListener("click", function()
			{
				oEditBox.style.display = "block";
				oEditBox.editButtonIndex = item.index; // 点击的行编辑按钮的序号传给编辑框，到时候编辑框知道要对哪一行进行编辑
			});
		});
	}
	// 行编辑按钮点击后的处理
	function editHandler()
	{
		let oEditBox = oSubscribeAutoReplyTextEditor.querySelector("#subscribeAutoReply_editBox"),
			aEditOption = Array.from( oEditBox.children );


		// 点击编辑框区域编辑框消失
		oEditBox.addEventListener("click", function(ev)
		{
			oEditBox.style.display = "none";
		});

		// 具体的行操作
		aEditOption.forEach(function(item)
		{
			item.addEventListener("click", function(ev)
			{
				switch( item.className )
				{
					case "deleteThisLine":
					{
						deleteThisLi( aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex] );
						break;
					}
					case "insertNewlineBefore":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "beforebegin", "newline");
						break;
					}
					case "insertLinkBefore":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "beforebegin", "link");
						break;
					}
					case "insertTextBefore":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "beforebegin", "text");
						break;
					}
					case "insertNewlineAfter":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "afterend", "newline");
						break;
					}
					case "insertLinkAfter":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "afterend", "link");
						break;
					}
					case "insertTextAfter":
					{
						insertLi(aSubscribeAutoReplyEditButton[oEditBox.editButtonIndex], "afterend", "text");
						break;
					}
				}
			});
		});
	}

	// 插入新行
	/*
	 * oThisLiEditBtn 参数：点击的行编辑按钮
	 * sPosition 参数是 "beforebegin"或"afterend"
	 * sType 有三种值："link"，"newline"，"text"
	 */
	function insertLi(oThisLiEditBtn, sPosition, sType)
	{
		let sHTML = "";
		switch(sType)
		{
			case "link":
			{
				sHTML = "<li class='subscribeAutoReply_link'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <span class='type'>链接文本：</span><input class='linkTextInput' type='text' value='' /> 链接地址：<input class='linkInput' type='text' value='' /></li>";
				break;
			}
			case "newline":
			{
				sHTML = "<li class='subscribeAutoReply_newline'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <input type='text' disabled='disabled' value='换行' /></li>";
				break;
			}
			case "text":
			{
				sHTML = "<li class='subscribeAutoReply_text'><input class='subscribeAutoReply_edit' type='button' value='行编辑' /> <span class='type'>文本：</span><input class='textInput' type='text' value='' /></li>";
				break;
			}
		}

		let oThisLi = oThisLiEditBtn.parentNode;
		oThisLi.insertAdjacentHTML(sPosition, sHTML);

		// 插入新行重新绑定
		lineEditAddEventHandler();
		// 更新行编辑按钮集合
		aSubscribeAutoReplyEditButton = Array.from( oEditorUl.querySelectorAll(".subscribeAutoReply_edit") );
	}

	// 删除该行
	function deleteThisLi(oThisLiEditBtn)
	{
		let oThisLi = oThisLiEditBtn.parentNode;
		oThisLi.parentNode.removeChild(oThisLi);
	}


	// 拷贝之前的JSON文件
	{
		oSubscribeAutoReplyTextEditor.querySelector(".copyPrevious").addEventListener("click", function()
		{
			AjaxGet("subscribeAutoReply/subscribeAutoReply.php?command=copySubscribeAutoReplyJSON",
				function(resonseText)
				{
					if( 'success' === resonseText.trim() )
					{
						alert("拷贝成功");
					}
					else
					{
						alert("拷贝失败");
					}
				},
				function(status)
				{
					alert("拷贝失败。错误码：" + status);
				});
		});
	}

	// 提交修改
	{
		oSubscribeAutoReplyTextEditor.querySelector(".submitAutoReplyText").addEventListener("click", function()
		{
			// 检查是否有空的文本框
			let bHasEmptyInput = Array.from( oEditorUl.querySelectorAll("input[type=text]") ).some(function(item)
			{
				if( item.value.trim() === "" )
				{
					item.style.backgroundColor = "red";
					item.addEventListener("focus", function()
					{
						item.style.backgroundColor = "transparent";
					});
					alert("有空的没填");
					return true;
				}
			});

			if( !bHasEmptyInput )
			{
				let aSubscribeAutoReplyTextLi = Array.from(oEditorUl.querySelectorAll("li")),
					aJSON = [];

				aSubscribeAutoReplyTextLi.forEach(function(item)
				{
					switch( item.className )
					{
						case "subscribeAutoReply_link" :
						{
							let sLink = item.querySelector(".linkInput").value,
								sLinkText = item.querySelector(".linkTextInput").value;
							aJSON.push( "<a href='" + sLink + "'>" + sLinkText + "</a>" );
							break;
						}
						case "subscribeAutoReply_newline" :
						{
							aJSON.push( "\\n" );
							break;
						}
						case "subscribeAutoReply_text" :
						{
							aJSON.push( item.querySelector(".textInput").value );
							break;
						}
					}
				});

				AjaxPost("subscribeAutoReply/subscribeAutoReply.php", "newJSON="+encodeURIComponent(JSON.stringify(aJSON)), function(responseText)
				{
					if( 'success' === responseText.trim() )
					{
						alert("修改成功");
					}
					else
					{
						alert("修改失败");
					}
				}, function()
				{
					alert("修改失败。错误码：" + status);
				});
			}
		});

	}

}



//fnSuccess可传参作为responseText的引用
function AjaxGet(sUrl, fnSuccess, fnFail)
{
	var xhr = new XMLHttpRequest();
	xhr.addEventListener('readystatechange', function()
	{
		if (xhr.readyState == 4)
		{
			if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304)
			{
			    fnSuccess(xhr.responseText);
			}
			else if(fnFail)
			{

			    fnFail(xhr.status);
			}
		}
	}, false);
	xhr.open("get", sUrl, true);
	xhr.send(null);

}
function AjaxPost(sUrl, sData, fnSuccess, fnFail)
{
	var xhr = new XMLHttpRequest();
	xhr.addEventListener('readystatechange', function()
	{
		if (xhr.readyState == 4)
		{
			if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304)
			{
			    fnSuccess(xhr.responseText);
			}
			else if(fnFail)
			{

			    fnFail(xhr.status);
			}
		}
	}, false);
	xhr.open("post", sUrl, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(sData);

}

</script>
</html>
