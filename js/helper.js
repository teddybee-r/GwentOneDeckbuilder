// helper.js

// get the parameter of GET ?variable=PARAMETER
function GET( variable )
{
    // get the url parameters, ignore ?
    var query = window.location.search.substring( 1 );
    var vars = query.split( "&" );

    for ( var i=0; i<vars.length; i++ )
    {
        var pair = vars[i].split( "=" );
        if( pair[0] == variable ){return pair[1];}
    }
    return( false );
}

// Click to play Gwent premium card
function premiumVideo(card, art) {
    var image = document.getElementById("premium");
    var video = `
    <video id="premium"  onclick="premiumVideo(`+card+`, `+art+`)" poster="https://gwent.one/img/assets/medium/art/`+art+`.jpg" class="premium__video" autoplay="yes" width="249" height="357">
        <source src="https://gwent.one/video/card/premium/`+card+`.webm" type="video/webm">
        <source src="https://gwent.one/video/card/premium/`+card+`.mp4" type="video/mp4">
    </video>`;
    image.outerHTML = video;
}


// html2canvas pushing to element #canvas
function h2c() {
    html2canvas(document.getElementById("Deck"), { allowTaint: true, backgroundColor: "rgba(0,0,0,0)"}).then(canvas => {
        document.getElementById("canvas").append(canvas)
    });
}

function h2c2cp() {
html2canvas(document.getElementById("Deck")).then(canvas => canvas.toBlob(blob => navigator.clipboard.write([new ClipboardItem({'image/png': blob})])));
}

// html2canvas pushing to download
function downloadDeck() {
  var element = document.getElementById("Deck");

  html2canvas(element, { allowTaint: true, backgroundColor: "rgba(0,0,0,0)"}).then(function(canvas) {
    download(canvas.toDataURL("image/png"));
  })
}
function download(url) {
    // create a new anchor tag
    var a = document.createElement('a');
    a.style.display = "none";
    a.setAttribute("href", url);
    a.setAttribute("download", "deck.png");
    // add anchor tag, click and remove it
    document.body.appendChild(a);
    a.click();
    a.remove();
}

// URL friendly base64 shortening
Base64 = {
    _Rixits :
//   0       8       16      24      32      40      48      56     63
//   v       v       v       v       v       v       v       v      v
    "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_",
    fromNumber : function(number) {
        if (isNaN(Number(number)) || number === null ||
            number === Number.POSITIVE_INFINITY)
            throw "The input is not valid";
        if (number < 0)
            throw "Can't represent negative numbers now";

        var rixit; 
        var residual = Math.floor(number);
        var result = '';
        while (true) {
            rixit = residual % 64
            result = this._Rixits.charAt(rixit) + result;
            residual = Math.floor(residual / 64);

            if (residual == 0)
                break;
            }
        return result;
    },

    toNumber : function(rixits) {
        var result = 0;
        rixits = rixits.split('');
        for (var e = 0; e < rixits.length; e++) {
            result = (result * 64) + this._Rixits.indexOf(rixits[e]);
        }
        return result;
    }
}