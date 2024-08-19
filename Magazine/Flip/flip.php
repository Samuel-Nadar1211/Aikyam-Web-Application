<?php
    session_start();
    if(!isset($_SESSION['login']))
        header("Location: ../Login/loginwithotp.php");
    if(time()-$_SESSION['login_time_stamp'] > 1800)  
    {
        session_unset();
        header("Location: ../Login/loginwithotp.php");
    }
?>

<html lang="en">
<head>
    <title>Aikyam</title>
    <meta name="viewport" content="width = 1050, user-scalable = no" />
    <link id='favicon' rel="shortcut icon" href="../../Image/favicon.png" type="image/x-png">
    <script type="text/javascript" src="extras/jquery.min.1.7.js"></script>
    <script type="text/javascript" src="extras/modernizr.2.5.3.min.js"></script>
    <script type="text/javascript" src="lib/hash.js"></script>
    <style>
		.page-navigation {
			position: absolute;
			left: 50%;
			transform: translate(-50%, -50%);
			border-radius: 10px;
			padding: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}
		
		.page-navigation input {
			width: 100px;
			padding: 3px;
			font-size: 14px;
			border-radius: 5px;
			border: 1px solid #ccc;
			margin-right: 5px;
		}
		
		.page-navigation button {
			padding: 5px 10px;
			font-size: 14px;
			border-radius: 5px;
			border: none;
			background-color: #ccc;
			cursor: pointer;
		}
		
		.page-navigation button:hover {
			background-color: #bbb;
		}
        .overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: black;
			opacity: 1;
			visibility: visible;
			animation: fadeOut 2.5s linear forwards; /* Adjust the duration as needed */
			z-index: 9999;
		}

		@keyframes fadeOut {
		from {
			opacity: 1;
		}
		to {
			opacity: 0;
			visibility: hidden;
		}
		}
	
    </style>
</head>

<body>
    <div class="overlay" id="overlay"></div>
        <div id="canvas">
            <img height="50" width="50" src="https://static.vecteezy.com/system/resources/previews/017/178/078/non_2x/cross-check-symbol-on-transparent-background-free-png.png" onclick="goBack()"></img>
            <div class="page-navigation">
                <input type="number" id="pageInput" placeholder="Page No.">
                <button onclick="navigateToPage()">Go</button>
            </div>
            <div class="zoom-icon zoom-icon-in"></div>
            <p><br></p>
            <div class="magazine-viewport">
                <div class="container">
                    <div class="magazine">
                        <!-- Next button -->
                        <div ignore="1" class="next-button"></div>
                        <!-- Previous button -->
                        <div ignore="1" class="previous-button"></div>
                    </div>
                </div>
            </div>

            <!-- Thumbnails -->
            <div class="thumbnails"></div>
        </div>
    </div>

	<script>
		function goBack() {
			window.location.href = "../index.html";
		}

		var issue, n;

		function loadApp() {
			$('#canvas').fadeIn(1000);
			var flipbook = $('.magazine');
			
			// Check if the CSS was already loaded
			if (flipbook.width() == 0 || flipbook.height() == 0) {
				setTimeout(loadApp, 10);
				return;
			}

			// Create the flipbook
			flipbook.turn({
				// Magazine width
				width: 922,
				// Magazine height
				height: 600,
				// Duration in millisecond
				duration: 1000,
				// Hardware acceleration
				acceleration: !isChrome(),
				// Enables gradients
				gradients: true,
				// Auto center this flipbook
				autoCenter: true,
				// Elevation from the edge of the flipbook when turning a page
				elevation: 50,
				// The number of pages
				pages: n,
				// Events
				when: {
					turning: function(event, page, view) {
						var book = $(this),
							currentPage = book.turn('page'),
							pages = book.turn('pages');

						// Update the current URI
						Hash.go('page/' + page).update();

						// Show and hide navigation buttons
						disableControls(page);

						$('.thumbnails .page-' + currentPage)
							.parent()
							.removeClass('current');
						$('.thumbnails .page-' + page)
							.parent()
							.addClass('current');
					},
					turned: function(event, page, view) {
						disableControls(page);
						$(this).turn('center');
						if (page == 1) {
							$(this).turn('peel', 'br');
						}
					},
					missing: function(event, pages) {
						// Add pages that aren't in the magazine
						for (var i = 0; i < pages.length; i++)
							addPage(pages[i], $(this));
					}
				}
			});

			// Zoom.js
			$('.magazine-viewport').zoom({
				flipbook: $('.magazine'),
				max: function() {
					return largeMagazineWidth() / $('.magazine').width();
				},
				when: {
					swipeLeft: function() {
						$(this).zoom('flipbook').turn('next');
					},
					swipeRight: function() {
						$(this).zoom('flipbook').turn('previous');
					},
					resize: function(event, scale, page, pageElement) {
						if (scale == 1)
							loadSmallPage(page, pageElement);
						else
							loadLargePage(page, pageElement);
					},
					zoomIn: function() {
						$('.thumbnails').hide();
						$('.made').hide();
						$('.magazine').removeClass('animated').addClass('zoom-in');
						$('.zoom-icon').removeClass('zoom-icon-in').addClass('zoom-icon-out');

						if (!window.escTip && !$.isTouch) {
							escTip = true;

							$('<div />', { 'class': 'exit-message' })
								.html('<div>Press ESC to exit</div>')
								.appendTo($('body'))
								.delay(2000)
								.animate({ opacity: 0 }, 500, function() {
									$(this).remove();
								});
						}
					},
					zoomOut: function() {
						$('.exit-message').hide();
						$('.thumbnails').fadeIn();
						$('.made').fadeIn();
						$('.zoom-icon').removeClass('zoom-icon-out').addClass('zoom-icon-in');

						setTimeout(function() {
							$('.magazine').addClass('animated').removeClass('zoom-in');
							resizeViewport();
						}, 0);
					}
				}
			});

			// Zoom event
			if ($.isTouch)
				$('.magazine-viewport').bind('zoom.doubleTap', zoomTo);
			else
				$('.magazine-viewport').bind('zoom.tap', zoomTo);

			// Using arrow keys to turn the page
			$(document).keydown(function(e) {
				var previous = 37,
					next = 39,
					esc = 27;
				switch (e.keyCode) {
					case previous:
						// left arrow
						$('.magazine').turn('previous');
						e.preventDefault();
						break;
					case next:
						//right arrow
						$('.magazine').turn('next');
						e.preventDefault();
						break;
					case esc:
						$('.magazine-viewport').zoom('zoomOut');
						e.preventDefault();
						break;
				}
			});

			// URIs - Format #/page/1 
			Hash.on('^page\/([0-9]*)$', {
				yep: function(path, parts) {
					var page = parts[1];
					if (page !== undefined) {
						if ($('.magazine').turn('is'))
							$('.magazine').turn('page', page);
					}
				},
				nop: function(path) {
					if ($('.magazine').turn('is'))
						$('.magazine').turn('page', 1);
				}
			});

			$(window).resize(function() {
				resizeViewport();
			}).bind('orientationchange', function() {
				resizeViewport();
			});

			// Events for thumbnails
			$('.thumbnails').click(function(event) {
				var page;
				if (event.target && (page = /page-([0-9]+)/.exec($(event.target).attr('class')))) {
					$('.magazine').turn('page', page[1]);
				}
			});

			$('.thumbnails li')
				.bind($.mouseEvents.over, function() {
					$(this).addClass('thumb-hover');
				})
				.bind($.mouseEvents.out, function() {
					$(this).removeClass('thumb-hover');
				});

			if ($.isTouch) {
				$('.thumbnails')
					.addClass('thumbanils-touch')
					.bind($.mouseEvents.move, function(event) {
						event.preventDefault();
					});
			} else {
				$('.thumbnails ul').mouseover(function() {
					$('.thumbnails').addClass('thumbnails-hover');
				}).mousedown(function() {
					return false;
				}).mouseout(function() {
					$('.thumbnails').removeClass('thumbnails-hover');
				});
			}

			// Regions
			if ($.isTouch) {
				$('.magazine').bind('touchstart', regionClick);
			} else {
				$('.magazine').click(regionClick);
			}

			// Events for the next button
			$('.next-button')
				.bind($.mouseEvents.over, function() {
					$(this).addClass('next-button-hover');
				})
				.bind($.mouseEvents.out, function() {
					$(this).removeClass('next-button-hover');
				})
				.bind($.mouseEvents.down, function() {
					$(this).addClass('next-button-down');
				})
				.bind($.mouseEvents.up, function() {
					$(this).removeClass('next-button-down');
				})
				.click(function() {
					$('.magazine').turn('next');
				});

			// Events for the previous button
			$('.previous-button')
				.bind($.mouseEvents.over, function() {
					$(this).addClass('previous-button-hover');
				})
				.bind($.mouseEvents.out, function() {
					$(this).removeClass('previous-button-hover');
				})
				.bind($.mouseEvents.down, function() {
					$(this).addClass('previous-button-down');
				})
				.bind($.mouseEvents.up, function() {
					$(this).removeClass('previous-button-down');
				})
				.click(function() {
					$('.magazine').turn('previous');
				});

			resizeViewport();

			$('.magazine').addClass('animated');
		}

		// Zoom icon
		$('.zoom-icon')
			.bind('mouseover', function() {
				if ($(this).hasClass('zoom-icon-in'))
					$(this).addClass('zoom-icon-in-hover');
				if ($(this).hasClass('zoom-icon-out'))
					$(this).addClass('zoom-icon-out-hover');
			})
			.bind('mouseout', function() {
				if ($(this).hasClass('zoom-icon-in'))
					$(this).removeClass('zoom-icon-in-hover');
				if ($(this).hasClass('zoom-icon-out'))
					$(this).removeClass('zoom-icon-out-hover');
			})
			.bind('click', function() {
				if ($(this).hasClass('zoom-icon-in'))
					$('.magazine-viewport').zoom('zoomIn');
				else if ($(this).hasClass('zoom-icon-out'))
					$('.magazine-viewport').zoom('zoomOut');
			});

		$('#canvas').hide();

		function getUrlParameter(name) {
			name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
			var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
			var results = regex.exec(location.search);
			return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
		}

		function loadPages() {
			issue = getUrlParameter('issue');

			fetch('book.json')
				.then(result => result.json())
				.then(data => {
					n = data[issue].page;
					loadApp();
				})
				.catch(error => console.error('Error loading pages:', error));
		}

		// Load the HTML4 version if there's not CSS transform

		yepnope({
			test: Modernizr.csstransforms,
			yep: ['lib/turn.js'],
			nope: ['lib/turn.html4.min.js'],
			both: ['lib/zoom.min.js', 'js/magazine.js', 'css/magazine.css'],
			complete: loadPages
		});

		function navigateToPage() {
			var pageNumber = parseInt(document.getElementById("pageInput").value);
			var totalPages = $('.magazine').turn('pages');
			var startingIndex = issue == 12 || issue >= 14 ? 1 : 0;

			var targetPage = pageNumber + startingIndex;

			if (isNaN(pageNumber) || targetPage < 1 || targetPage > totalPages) {
				alert("Page not found. Please enter a valid page number.");
			} else {
				$('.magazine').turn('page', targetPage);
			}
		}
	</script>
</body>
</html>