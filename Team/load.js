async function myDoc() {
  const response = await fetch('members.json');
  const names = await response.json();

  var team = document.getElementsByClassName("team");
  var script = ``;
  for (i = 0; i < names.length; i++) {
    script = script + `
        <div class="container2">
      <h3 class="heading" style="text-align: center; margin-bottom: 5px;">`+ names[i].title + ` <span>Aikyam Team</span>
      </h3>
      <br>
      <div class="team-grid">
        <!--team item start-->
      `

    for (j = 0; j < names[i].members.length; j++) {

      script = script + `
        <div class="item-team">
          <div class="item-team-img-box">
            <img src="`+ names[i].members[j].image + `" alt="img" />
          </div>
          <div class="item-team-text">
            <div class="item-team-number">
              <span data-text="0">0</span>
              <span data-text="`+ (j + 1) + `">` + (j + 1) + `</span>
            </div>
            <div class="item-team-line"></div>
            <div class="item-team-name">`+ names[i].members[j].name + `</div>
          </div>
          <div class="item-team-hover">
            <br />
            <br />
            <br />
            <div class="item-team-social-links" style="--i: 2">
              <div class="social">
                <ul class="Social-Media">
                  <li>
                    <a href="`+ names[i].members[j].linkedin + `" target="_blank">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <i class="fab fa-linkedin" aria-hidden="true"></i>
                    </a>
                  </li>
                  <li>
                    <a href="`+ names[i].members[j].instagram + `" target="_blank">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      `
    }
    script = script + `
      <br>  
      </div>
    </div>
    `
  }
  team[0].innerHTML = script;
}