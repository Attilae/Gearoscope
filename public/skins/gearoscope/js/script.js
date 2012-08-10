// JavaScript Document
$(document).ready(function(){
	 $('<a class="next" href="#"></a><a class="prev" href="#"></a>').appendTo('.moduletable-top_video');						
     function sfas(){n=++n<maxN?n:0
     $('ul.top-video ')
     .stop()
     .animate({
     top:-step*n}, 600, "easeOutBack")};
     var n=0,
     maxN=$('ul.top-video li').length-1,
     step=251,
     timer=setInterval(sfas,10000)
     
     $('.moduletable-top_video .next').click(function(){
     n=++n<maxN?n:0
     $('ul.top-video ')
     .stop()
     .animate({
     top:-step*n
     }, 600, "easeOutBack")
     clearInterval(timer);
     timer=setInterval(sfas,10000);
	 return false;
     });
     
     
     $('.moduletable-top_video .prev').click(function(){
     n=--n>=0?n:maxN-1
     $('ul.top-video ')
     .stop()
     .animate({
     top:-step*n
     }, 600, "easeOutBack")
     clearInterval(timer);
     timer=setInterval(sfas,10000);
	 return false;
	 
     });
     $('ul.top-video ').hover(function(){clearInterval(timer)},function(){timer=setInterval(sfas,10000)});
	 
	 $('<div class="buttons"><a class="next" href="#"></a><a class="prev" href="#"></a></div>').appendTo('.moduletable-news');						
     function sfas2(){n2=++n2<maxN2?n2:0
     $('ul.scroller ')
     .stop()
     .animate({
     top:-step2*n2}, 600, "easeOutBack")};
     var n2=0,
     maxN2=$('ul.scroller > li').length-4,
     step2=28,
     timer2=setInterval(sfas2,8000)
     
     $('.moduletable-news .next').click(function(){
     n2=++n2<maxN2?n2:0
     $('ul.scroller ')
     .stop()
     .animate({
     top:-step2*n2
     }, 600, "easeOutBack")
     clearInterval(timer2);
     timer2=setInterval(sfas2,8000);
	 return false;
     });
     
     
     $('.moduletable-news .prev').click(function(){
     n2=--n2>=0?n2:maxN2-1
     $('ul.scroller ')
     .stop()
     .animate({
     top:-step2*n2
     }, 600, "easeOutBack")
     clearInterval(timer2);
     timer2=setInterval(sfas2,8000);
	 return false;
	 
     });
     $('ul.scroller ').hover(function(){clearInterval(timer2)},function(){timer2=setInterval(sfas2,8000)});
	 
	 $('<a class="next" href="#"></a><a class="prev" href="#"></a>').appendTo('.NivoSzakiSlider');						
     var n3=0,
     maxN3=3,
     step3=143
     
     $('.NivoSzakiSlider .next').click(function(){
     n3=++n3<maxN3?n3:0
     $('ul.nivo-controlNav ')
     .stop()
     .animate({
     top:-step3*n3
	 }, 600, "easeOutBack")
	 return false;
     });
     
     
     $('.NivoSzakiSlider .prev').click(function(){
     n3=--n3>=0?n3:maxN3-1
     $('ul.nivo-controlNav ')
     .stop()
     .animate({
     top:-step3*n3
     }, 600, "easeOutBack")
	 return false;
     });
    });