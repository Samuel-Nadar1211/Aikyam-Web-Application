function loadMagazine() {
    fetch('Flip/book.json')
        .then(result => { return result.json() })
        .then(data => {

            let n = 0;
            for (let key in data) {
                n++;
            }

            for (var i = n; i > 0; i--) {
                var string = '<div class="magazine">\
                    <img src="Flip/Image/Aikyam - ' + i + '/1.jpg' + '" class="coverpage">\
                    <h2 class="magazineName" style="text-align: center;">Aikyam - ' + i + '</h2>\
                    <button class="view" onclick="getIssue(' + i + ')">View</button>\
                    <a href="download.php?issue=' + i + '">\
                        <button class="download">Download</button>\
                    </a>\
                </div>'

                document.getElementById('magazineList').innerHTML += string;
            }
        });
}

function getIssue(n) {
    window.location.href = "Flip/flip.php?issue=" + encodeURIComponent(n);
};