var stmnLEFT = 800; // 스크롤메뉴의 좌측 위치. 필요 없을 경우 삭제
var stmnGAP1 = 10; // 페이지 헤더부분의 여백 (이보다 위로는 올라가지 않음)
var stmnGAP2 = 10; // 스크롤시 브라우저 상단과 약간 띄움. 필요없으면 0으로 세팅
var stmnBASE = 80; // 스크롤메뉴 초기 시작위치 (아무렇게나 해도 상관은 없지만 stmnGAP1과 약간 차이를 주는게 보기 좋음)
var stmnActivateSpeed = 150; // 움직임을 감지하는 속도 (숫자가 클수록 늦게 알아차림)
var stmnScrollSpeed = 10; // 스크롤되는 속도 (클수록 늦게 움직임)

var stmnTimer;

// 쿠키 읽기
function ReadCookie(name)
{
        var label = name + "=";
        var labelLen = label.length;
        var cLen = document.cookie.length;
        var i = 0;

        while (i < cLen) {
                var j = i + labelLen;

                if (document.cookie.substring(i, j) == label) {
                        var cEnd = document.cookie.indexOf(";", j);
                        if (cEnd == -1) cEnd = document.cookie.length;

                        return unescape(document.cookie.substring(j, cEnd));
                }
        
                i++;
        }

        return "";
}

// 쿠키 저장
function SaveCookie(name, value, expire)
{
        var eDate = new Date();
        eDate.setDate(eDate.getDate() + expire);
        document.cookie = name + "=" + value + "; expires=" +  eDate.toGMTString()+ "; path=/";
}

// 스크롤 메뉴의 위치 갱신
function RefreshStaticMenu()
{
try{
        var stmnStartPoint, stmnEndPoint, stmnRefreshTimer;

        stmnStartPoint = parseInt(STATICMENU.style.top, 10);
        stmnEndPoint = document.body.scrollTop + stmnGAP2;
        if (stmnEndPoint < stmnGAP1) stmnEndPoint = stmnGAP1;

        stmnRefreshTimer = stmnActivateSpeed;

        if ( stmnStartPoint != stmnEndPoint ) {
                stmnScrollAmount = Math.ceil( Math.abs( stmnEndPoint - stmnStartPoint ) / 15 );
                STATICMENU.style.top = parseInt(STATICMENU.style.top, 10) + ( ( stmnEndPoint<stmnStartPoint ) ? -stmnScrollAmount : stmnScrollAmount );
                stmnRefreshTimer = stmnScrollSpeed;
        }

        stmnTimer = setTimeout ("RefreshStaticMenu();", stmnRefreshTimer);
}catch(e){}
}

// 메뉴 ON/OFF 하기
function ToggleAnimate()
{
		//alert("aa")
        if (ANIMATE.checked) { // 이동하기 버튼이 체크되었다면
                RefreshStaticMenu(); // 메뉴위치를 다시 조정
                SaveCookie("ANIMATE", "true", 300); // 이동이 ON 상태라고 쿠키를 설정
        }
        else { // 아니라면... (이동하기 버튼이 체크되어 있지 않으면)
                clearTimeout(stmnTimer); // 이동용 타이머 해제
                STATICMENU.style.top = stmnGAP1; // 메뉴의 위치를 상단으로 옮긴다.
                SaveCookie("ANIMATE", "false", 300); // 이동상태가 "OFF" 임
        }
}

// 메뉴 초기화
function InitializeStaticMenu()
{
try{
        if (ReadCookie("ANIMATE") == "false") { // 이동상태가 off 상태라면
                ANIMATE.checked = false; // 체크표시를 지우고
                STATICMENU.style.top = document.body.scrollTop + stmnGAP1; // 맨 위에 들러 붙는다.
        }
        else { // 이동 on 상태라면
                ANIMATE.checked = true; // 체크표시를 하고
                STATICMENU.style.top = document.body.scrollTop + stmnBASE; // 기본위치로 이동한다.
                RefreshStaticMenu(); // 스크립트 가동
        }

        //STATICMENU.style.left = stmnLEFT; // 메뉴 왼쪽 위치 초기화. 필요없을 경우 삭제
}catch(e){}
}

function Open_Romas()
{
	window.open("http://urimal.cs.pusan.ac.kr/romanWebDll/romanweb.htm","Romas", "width=420,height=520,toolbar=no,scrollbars=no,resizable=no, status=no,left=600,top=0,leftmargin=0,topmargin=0");	
}