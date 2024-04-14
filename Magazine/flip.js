const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");
const book = document.getElementById("book");

prevBtn.addEventListener("click", goPrevPage);
nextBtn.addEventListener("click", goNextPage);

let currentLocation = 0;
let numOfPaper;

function openBook() {
    book.style.transform = "translateX(50%)";
    prevBtn.style.transform = "translateX(-180px)";
    nextBtn.style.transform = "translateX(180px)";
}

function closeBook(isAtBeginning) {
    if (isAtBeginning) {
        book.style.transform = "translateX(0%)";
    }
    else {
        book.style.transform = "translateX(100%)";
    }

    prevBtn.style.transform = "translateX(0px)";
    nextBtn.style.transform = "translateX(0px)";
}

function goNextPage() {
    if (currentLocation < numOfPaper) {
        const paper = document.getElementById('p' + (currentLocation));

        if (currentLocation == 0)
            openBook();
        else if (currentLocation == numOfPaper - 1)
            closeBook(false);

        paper.classList.add("flipped");
        paper.style.zIndex = paper.style.zIndex >= 0 ? paper.style.zIndex : -paper.style.zIndex;
        currentLocation++;
    }
}

function goPrevPage() {
    if (currentLocation > 0) {
        currentLocation--;
        const paper = document.getElementById('p' + (currentLocation));

        if (currentLocation == 0)
            closeBook(true);
        else if (currentLocation == numOfPaper - 1)
            openBook();

        paper.classList.remove("flipped");
        paper.style.zIndex = paper.style.zIndex > 0 ? -paper.style.zIndex : paper.style.zIndex;
    }
}

function addPage(folder, n, imageFormat) {
    numOfPaper = n / 2;

    for (var i = 1; i < n; i += 2) {

        var string = '<div class="paper" id="p' + Math.floor(i / 2) + '" style="z-index: -' + Math.floor((i + 1) / 2) + ';">\
            <div class="front">\
                <div class="front-content">\
                    <img src="' + folder + i + '.' + imageFormat + '">\
                </div>\
            </div>\
            <div class="back">\
                <div class="back-content">\
                    <img src="' + folder + (i + 1) + '.' + imageFormat + '">\
                </div>\
            </div>\
        </div>'

        book.innerHTML += string;
    }
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

function loadPage() {
    addPage(getUrlParameter('folder') + '/', getUrlParameter('n'), getUrlParameter('imageFormat'));
}