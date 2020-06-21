
function flip(event){
    let obj = event.srcElement;  //event在ie中自带有，可以不用传入，其他少数浏览器需要传入
    let pageNow = obj.id;

    for(let i = 1; i < 6; i ++) {
        let page = document.getElementById(i);
        let numID = i + 10;
        let num = document.getElementById(numID);
        if(page != null) {
            if (i == (pageNow - 10)) {
                page.style.display = "";
                num.style.color = "blue";
            } else {
                page.style.display = "none";
                num.style.color = "black";
            }
        }
    }
}

function pre() {
    let nowPage = {};
    let nowNum = 0;
    for(let i = 1; i < 6; i ++) {
        let page = document.getElementById(i);
        if(page.style.display != "none") {
            nowPage = page;
            nowNum = nowPage.id;
            break;
        }
    }
    if(nowNum == 1) {
        alert("当前页面已经是第一页了");
    } else {
        let preNum = nowNum - 1;
        let preTip = preNum + 10;
        let nowTip = "1" + nowNum;
        nowPage.style.display = "none";
        document.getElementById(preNum).style.display = "";
        document.getElementById(nowTip).style.color = "black";
        document.getElementById(preTip).style.color = "blue";
    }
}

function next() {
    chooseCss();
    let nowPage = {};
    let nowNum = 0;
    for(let i = 1; i < 6; i ++) {
        let page = document.getElementById(i);
        if(page.style.display != "none") {
            nowPage = page;
            nowNum = nowPage.id;
            break;
        }
    }

    let nextNum = parseInt(nowNum) + 1;
    if(document.getElementById(nextNum) != null) {
        let nextTip = nextNum + 10;
        let nowTip = "1" + nowNum;
        nowPage.style.display = "none";
        document.getElementById(nextNum).style.display = "";
        document.getElementById(nowTip).style.color = "black";
        document.getElementById(nextTip).style.color = "blue";
    } else {
        alert("当前页面已经是最后一页了");
    }
}
