function rightIFrameLoad(iframe) {   
    var pHeight = getWindowInnerHeight() - $('#header').height() - 5;   
       
    $('div.body').height(pHeight);   
    console.log(pHeight);   
       
}   
  
// 浏览器兼容 取得浏览器可视区高度   
function getWindowInnerHeight() {   
    var winHeight = window.innerHeight   
            || (document.documentElement && document.documentElement.clientHeight)   
            || (document.body && document.body.clientHeight);   
    return winHeight;   
       
}   
  
// 浏览器兼容 取得浏览器可视区宽度   
function getWindowInnerWidth() {   
    var winWidth = window.innerWidth   
            || (document.documentElement && document.documentElement.clientWidth)   
            || (document.body && document.body.clientWidth);   
    return winWidth;   
       
}   
  
/**  
 * 显示遮罩层  
 */  
function showOverlay() {   
    // 遮罩层宽高分别为页面内容的宽高   
    $('.overlay').css({'height':$(document).height(),'width':$(document).width()});   
    $('.overlay').show();   
}   
  
/**  
 * 显示Loading提示  
 */  
function showLoading() {   
    // 先显示遮罩层   
    showOverlay();   
    // Loading提示窗口居中   
    $("#loadingTip").css('top',   
            (getWindowInnerHeight() - $("#loadingTip").height()) / 2 + 'px');   
    $("#loadingTip").css('left',   
            (getWindowInnerWidth() - $("#loadingTip").width()) / 2 + 'px');   
               
    $("#loadingTip").show();   
    $(document).scroll(function() {   
        return false;   
    });   
}   
  
/**  
 * 隐藏Loading提示  
 */  
function hideLoading() {   
    $('.overlay').hide();   
    $("#loadingTip").hide();   
    $(document).scroll(function() {   
        return true;   
    });   
}   
  
/**  
 * 模拟弹出模态窗口DIV  
 * @param innerHtml 模态窗口HTML内容  
 */  
function showModal(innerHtml) {   
    // 取得显示模拟模态窗口用DIV   
    var dialog = $('#modalDiv');   
       
    // 设置内容   
    dialog.html(innerHtml);   
       
    // 模态窗口DIV窗口居中   
    dialog.css({   
        'top' : (getWindowInnerHeight() - dialog.height()) / 2 + 'px',   
        'left' : (getWindowInnerWidth() - dialog.width()) / 2 + 'px'  
    });   
       
    // 窗口DIV圆角   
    dialog.find('.modal-container').css('border-radius','6px');   
       
    // 模态窗口关闭按钮事件   
    dialog.find('.btn-close').click(function(){   
        closeModal();   
    });   
       
    // 显示遮罩层   
    showOverlay();   
       
    // 显示遮罩层   
    dialog.show();   
}   
  
/**  
 * 模拟关闭模态窗口DIV  
 */  
function closeModal() {   
    $('.overlay').hide();   
    $('#modalDiv').hide();   
    $('#modalDiv').html('');   
}