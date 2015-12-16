<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mon blog</title>
    <?php include 'head.php' ?>
</head>

<body>

    <div id="fullpage">
        <div class="section">
            <h1>HOME</h1>
            <?php include '../forms/preRegisterForm.php' ?>
        </div>
        <div class="section">
            <h1>HOME</h1>
            <div class="slide"> Slide 1 </div>
            <div class="slide"> Slide 2 </div>
            <div class="slide"> Slide 3 </div>
            <div class="slide"> Slide 4 </div>
        </div>
        <div class="section">
            <h1>RECUPERER VOS FLUX DE DONNE</h1>
        </div>
        <div class="section">
            <h1>RECUPERER VOS FLUX DE DONNE</h1>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#fullpage').fullpage({
                //Navigation
                menu: '#menu',
                lockAnchors: false,
                anchors:['firstPage', 'secondPage'],
                navigation: false,
                navigationPosition: 'right',
                navigationTooltips: ['firstSlide', 'secondSlide'],
                showActiveTooltip: false,
                slidesNavigation: true,
                slidesNavPosition: 'bottom',

                //Scrolling
                css3: true,
                scrollingSpeed: 700,
                autoScrolling: true,
                fitToSection: true,
                fitToSectionDelay: 1000,
                scrollBar: false,
                easing: 'easeInOutCubic',
                easingcss3: 'ease',
                loopBottom: false,
                loopTop: false,
                loopHorizontal: true,
                continuousVertical: false,
                normalScrollElements: '#element1, .element2',
                scrollOverflow: false,
                touchSensitivity: 15,
                normalScrollElementTouchThreshold: 5,

                //Accessibility
                keyboardScrolling: true,
                animateAnchor: true,
                recordHistory: true,

                //Design
                controlArrows: true,
                verticalCentered: true,
                resize : false,
                sectionsColor : ['#ccc', '#fff'],
                paddingTop: '3em',
                paddingBottom: '10px',
                fixedElements: '#header, .footer',
                responsiveWidth: 0,
                responsiveHeight: 0,

                //Custom selectors
                sectionSelector: '.section',
                slideSelector: '.slide',

                //events
                onLeave: function(index, nextIndex, direction){},
                afterLoad: function(anchorLink, index){},
                afterRender: function(){},
                afterResize: function(){},
                afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){},
                onSlideLeave: function(anchorLink, index, slideIndex, direction, nextSlideIndex){}
            });
        });
    </script>
</body>
</html>