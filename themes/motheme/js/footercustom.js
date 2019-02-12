/* self collapse nav */
var $mg=jQuery.noConflict();
$mg(document).ready(function() {
    $mg('.latest-reviews').slick({
        dots: false,
        slidesToShow: 6,
        slidesToScroll: 0.1,
        arrows: false,
        infinite: true,
        speed: 300,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 0,
		focusOnSelect: true,
        pauseOnHover:true,
		touchMove:true,
        responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 414,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              }
            }
        ]
    });

    $mg('.scrollmenu').slick({
		dots: false,
		infinite: false,
		speed: 300,
		slidesToShow: 1,
		centerMode: false,
		variableWidth: true,
		prevArrow: '<button class="slick-prev slick-arrow"><i class="fa fa-chevron-left"></i></button>',
		nextArrow: '<button class="slick-next slick-arrow"><i class="fa fa-chevron-right"></i></button>'
		
	});

	jQuery('.scrollmenu').bind('touchstart', function(){ console.log('touchstart') });
});

var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
	selElmnt = x[i].getElementsByTagName("select")[0];
	/*for each element, create a new DIV that will act as the selected item:*/
	a = document.createElement("DIV");
	a.setAttribute("class", "select-selected");	
	a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
	x[i].appendChild(a);
	/*for each element, create a new DIV that will contain the option list:*/
	b = document.createElement("DIV");
	b.setAttribute("class", "select-items select-hide");
	for (j = 1; j < selElmnt.length; j++) {
	/*for each option in the original select element,
	create a new DIV that will act as an option item:*/
	c = document.createElement("DIV");
	c.innerHTML = selElmnt.options[j].innerHTML;
	c.addEventListener("click", function(e) {
	    /*when an item is clicked, update the original select box,
	    and the selected item:*/
	    var y, i, k, s, h;
	    s = this.parentNode.parentNode.getElementsByTagName("select")[0];

	    h = this.parentNode.previousSibling;
	    for (i = 0; i < s.length; i++) {
			if (s.options[i].innerHTML == this.innerHTML) {
				s.selectedIndex = i;
				h.innerHTML = this.innerHTML;
				y = this.parentNode.getElementsByClassName("same-as-selected");
				for (k = 0; k < y.length; k++) {
				  	y[k].removeAttribute("class");
				}
				this.setAttribute("class", "same-as-selected");
				window.location = s.value;
				break;
			}
	    }
	    h.click();
	});
	b.appendChild(c);
	}
	x[i].appendChild(b);
	a.addEventListener("click", function(e) {
		/*when the select box is clicked, close any other select boxes,
		and open/close the current select box:*/
		e.stopPropagation();
		closeAllSelect(this);
		this.nextSibling.classList.toggle("select-hide");
		this.classList.toggle("select-arrow-active");
    });
}

function closeAllSelect(elmnt) {
	/*a function that will close all select boxes in the document,
	except the current select box:*/
	var x, y, i, arrNo = [];
	x = document.getElementsByClassName("select-items");
	y = document.getElementsByClassName("select-selected");

	for (i = 0; i < y.length; i++) {
		if (elmnt == y[i]) {
	  		arrNo.push(i)
		} else {
		  	y[i].classList.remove("select-arrow-active");
		}
	}
	for (i = 0; i < x.length; i++) {
		if (arrNo.indexOf(i)) {
		  	x[i].classList.add("select-hide");
		}
	}
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);