var stmnLEFT = 800; // ��ũ�Ѹ޴��� ���� ��ġ. �ʿ� ���� ��� ����
var stmnGAP1 = 10; // ������ ����κ��� ���� (�̺��� ���δ� �ö��� ����)
var stmnGAP2 = 10; // ��ũ�ѽ� ������ ��ܰ� �ణ ���. �ʿ������ 0���� ����
var stmnBASE = 80; // ��ũ�Ѹ޴� �ʱ� ������ġ (�ƹ����Գ� �ص� ����� ������ stmnGAP1�� �ణ ���̸� �ִ°� ���� ����)
var stmnActivateSpeed = 150; // �������� �����ϴ� �ӵ� (���ڰ� Ŭ���� �ʰ� �˾�����)
var stmnScrollSpeed = 10; // ��ũ�ѵǴ� �ӵ� (Ŭ���� �ʰ� ������)

var stmnTimer;

// ��Ű �б�
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

// ��Ű ����
function SaveCookie(name, value, expire)
{
        var eDate = new Date();
        eDate.setDate(eDate.getDate() + expire);
        document.cookie = name + "=" + value + "; expires=" +  eDate.toGMTString()+ "; path=/";
}

// ��ũ�� �޴��� ��ġ ����
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

// �޴� ON/OFF �ϱ�
function ToggleAnimate()
{
		//alert("aa")
        if (ANIMATE.checked) { // �̵��ϱ� ��ư�� üũ�Ǿ��ٸ�
                RefreshStaticMenu(); // �޴���ġ�� �ٽ� ����
                SaveCookie("ANIMATE", "true", 300); // �̵��� ON ���¶�� ��Ű�� ����
        }
        else { // �ƴ϶��... (�̵��ϱ� ��ư�� üũ�Ǿ� ���� ������)
                clearTimeout(stmnTimer); // �̵��� Ÿ�̸� ����
                STATICMENU.style.top = stmnGAP1; // �޴��� ��ġ�� ������� �ű��.
                SaveCookie("ANIMATE", "false", 300); // �̵����°� "OFF" ��
        }
}

// �޴� �ʱ�ȭ
function InitializeStaticMenu()
{
try{
        if (ReadCookie("ANIMATE") == "false") { // �̵����°� off ���¶��
                ANIMATE.checked = false; // üũǥ�ø� �����
                STATICMENU.style.top = document.body.scrollTop + stmnGAP1; // �� ���� �鷯 �ٴ´�.
        }
        else { // �̵� on ���¶��
                ANIMATE.checked = true; // üũǥ�ø� �ϰ�
                STATICMENU.style.top = document.body.scrollTop + stmnBASE; // �⺻��ġ�� �̵��Ѵ�.
                RefreshStaticMenu(); // ��ũ��Ʈ ����
        }

        //STATICMENU.style.left = stmnLEFT; // �޴� ���� ��ġ �ʱ�ȭ. �ʿ���� ��� ����
}catch(e){}
}

function Open_Romas()
{
	window.open("http://urimal.cs.pusan.ac.kr/romanWebDll/romanweb.htm","Romas", "width=420,height=520,toolbar=no,scrollbars=no,resizable=no, status=no,left=600,top=0,leftmargin=0,topmargin=0");	
}