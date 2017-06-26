// 브라우저 종류와 버전 체크하는 객체 생성자 함수
function objDetectBrowser() {
  var strUA, s, i;
  this.isIE = false;  // 인터넷 익스플로러인지를 나타내는 속성
  this.isNS = false;  // 넷스케이프인지를 나타내는 속성
  this.version = null; // 브라우저 버전을 나타내는 속성
  // Agent 정보를 담고 있는 문자열.
  // 이 값이 궁금한 사람은 alert 문을 이용하여 strUA 값을 확인하기 바란다!
  strUA = navigator.userAgent; 
 
  s = "MSIE";
  // Agent 문자열(strUA) "MSIE"란 문자열이 들어 있는지 체크
  if ((i = strUA.indexOf(s)) >= 0) {
    this.isIE = true;
    // 변수 i에는 strUA 문자열 중 MSIE가 시작된 위치 값이 들어있고,
    // s.length는 MSIE의 길이 즉, 4가 들어 있다.
    // strUA.substr(i + s.length)를 하면 strUA 문자열 중 MSIE 다음에 
    // 나오는 문자열을 잘라온다.
    // 그 문자열을 parseFloat()로 변환하면 버전을 알아낼 수 있다.
    this.version = parseFloat(strUA.substr(i + s.length));
    return;
  }
 
  s = "Netscape6/";
  // Agent 문자열(strUA) "Netscape6/"이란 문자열이 들어 있는지 체크
  if ((i = strUA.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = parseFloat(strUA.substr(i + s.length));
    return;
  }
 
  // 다른 "Gecko" 브라우저는 NS 6.1로 취급.
 
  s = "Gecko";
  if ((i = strUA.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = 6.1;
    return;
  }
}
 
var objDetectBrowser = new objDetectBrowser();
 
// 현재 활성화된 버튼을 추적하기 위한 전역 변수.
var gvActiveButton = null;
 
// 버튼이 아닌 다른 곳에 마우스를 클릭하면 활성화된 버튼을 비활성화로 변경.
 
if (objDetectBrowser.isIE)
  document.onmousedown = mousedownPage;
if (objDetectBrowser.isNS)
  document.addEventListener("mousedown", mousedownPage, true);
 
function mousedownPage(event) {
 
  var objElement;
 
  // 활성화된 버튼이 없으면 밖으로 빠져 나감.
  if (!gvActiveButton)
    return;
 
  // 현재 선택된 객체 요소를 얻어 옴.
  if (objDetectBrowser.isIE)
    objElement = window.event.srcElement;
  if (objDetectBrowser.isNS)
    objElement = (event.target.className ? event.target : event.target.parentNode);
 
  // 만일 현재 활성화된 버튼을 클릭했다면 그냥 밖으로 빠져 나감.
  if (objElement == gvActiveButton)
    return;
 
  // 만일 클릭한 요소가 메뉴 버튼, 메뉴 아이템 등이 아니면 활성화된 메뉴를 비활성화 시킴.
  if (objElement.className != "menuButton"  && objElement.className != "menuItem" &&
      objElement.className != "menuItemSep" && objElement.className != "menu")
    resetButton(gvActiveButton);
}
 
function mouseoverButton(objMnuButton, strMenuName) {
 
  // 만일 다른 메뉴 버튼이 활성화되어 있다면 비활성화 시킨 후
  // 현재 마우스 오버된 메뉴를 활성화 시킨다.
 
  if (gvActiveButton && gvActiveButton != objMnuButton) {
    resetButton(gvActiveButton);
    
  if (strMenuName)
    clickButton(objMnuButton, strMenuName);
  }
}
 
function clickButton(objMnuButton, strMenuName) {
 
  // 링크 주변의 아웃라인을 없앰.
  objMnuButton.blur();
  
  // 이 메뉴 버튼에 하위 풀다운 메뉴 객체를 관장할
  // menu 란 이름의 객체 생성
  if (!objMnuButton.menu)
    objMnuButton.menu = document.getElementById(strMenuName);
 
 
  // 현재 활성화된 메뉴 버튼을 처음 상태로 되돌림.
  if (gvActiveButton && gvActiveButton != objMnuButton)
      resetButton(gvActiveButton);
 
  // 메뉴 버튼 활성화 여부에 따라 활성화/비활성화 토글.
  if (gvActiveButton)
    resetButton(objMnuButton);
  else
    pulldownMenu(objMnuButton);
 
  return false;
}
 
function pulldownMenu(objMnuButton) {
 
  // 현재 선택된 객체의 클래스를 "활성화" 클래스로 변경
  objMnuButton.className = "menuButtonActive";
  
  // 익스플로러의 경우, 첫 번째 메뉴 아이템에 대한 명확한 폭을 명시해 주도록 한다.
  // 만일 이 부분을 설정하지 않으면 마우스로 메뉴 아이템 오버시 텍스트 위에 올려놓을 때만
  // 반전된다. 만일 텍스트가 아닌 메뉴 아이템 영역 위로만 갖다 놔도 반전시키려면
  // 이 부분을 설정해 줘야 한다.
  if (objDetectBrowser.isIE && !objMnuButton.menu.firstChild.style.width) {
    objMnuButton.menu.firstChild.style.width = objMnuButton.menu.firstChild.offsetWidth + "px";
  }
  
  // 브라우저마다 각자 환경에 맞는 드롭 다운 메뉴의 위치를 
  // 결정해 줘야 한다.
  x = objMnuButton.offsetLeft;
  y = objMnuButton.offsetTop + objMnuButton.offsetHeight;
  if (objDetectBrowser.isIE) {
    x += 2;
    y += 2;
  }
  if (objDetectBrowser.isNS && objDetectBrowser.version < 6.1)
    y--;
 
  // 위치 결정 및 풀다운 메뉴를 보여줌
 
  objMnuButton.menu.style.left = x + "px";
  objMnuButton.menu.style.top  = y + "px";
  objMnuButton.menu.style.visibility = "visible";
  
  // 현재 활성화된 메뉴 객체를 저장하는 전역변수 gvActiveButon에
  // 현재 선택된 메뉴 객체를 설정
  gvActiveButton = objMnuButton;
}
 
function resetButton(objMnuButton) {
 
  // 원래 스타일로 되돌림
  objMnuButton.className = "menuButton";
 
  // 펼쳐진 풀다운 메뉴를 감춰줌
  if (objMnuButton.menu)
    objMnuButton.menu.style.visibility = "hidden";
 
  // 현재 활성화된 메뉴 버튼이 없는 것으로 설정
  gvActiveButton = null;
}