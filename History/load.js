async function myDoc() {
    const response = await fetch('history.json');
    const names = await response.json();
    const Timeline = document.getElementsByClassName("timeline");

    for (i = 0; i < names.length; i++) {
        const container = document.createElement("div");

        const image = document.createElement("img");
        image.src = "../Image/aikyam.jpg";
        image.alt = "img";
        container.appendChild(image);

        const box = document.createElement("div");
        if (i % 2 == 0)
            container.className = "container left-container";
        else
            container.className = "container right-container";


        box.className = "text-box";

        const head = document.createElement("h2");
        const period = document.createElement("small");
        const desc = document.createElement("p");

        const arrow = document.createElement("span");
        if (i % 2 == 0)
            arrow.className = "left-container-arrow";
        else
            arrow.className = "right-container-arrow";

        head.innerHTML = names[i].title;
        period.innerHTML = names[i].time;
        desc.innerHTML = names[i].desc;

        box.appendChild(head);
        box.appendChild(period);
        box.appendChild(desc);
        box.appendChild(arrow);
        container.appendChild(box);

        Timeline[0].appendChild(container);
    }
}