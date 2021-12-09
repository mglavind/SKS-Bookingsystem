jQuery.fn.shuffle = function () {
    var j;
    for (var i = 0; i < this.length; i++) {
        j = Math.floor(Math.random() * this.length);
        $(this[i]).before($(this[j]));
    }
    return this;
};
// mount
function update() {
    var grid = new Minigrid({
        container: '.cards',
        item: '.card',
        gutter: 10
    });
    grid.mount();
}

window.addEventListener('resize', update);

// Initialize Firebase
var config = {
    apiKey: "AIzaSyDWccpRWNUTqOo4LLv5jtRtyxLQKHLJzvA",
    authDomain: "vorkblog.firebaseapp.com",
    databaseURL: "https://vorkblog.firebaseio.com",
    storageBucket: "vorkblog.appspot.com",
    messagingSenderId: "143318163919"
};
firebase.initializeApp(config);

var database = firebase.database();
var postListRef = firebase.database().ref('posts');

var postText = $("#postText");
var submitBtn = $("#submitBtn");

function submitClick() {
    var newPostRef = postListRef.push();
    newPostRef.set({
        post: postText.val(),
        slettet: 0,
        godkendt: 0,
        time : Date.now()
    });
    $("#modal_indsend").modal('hide');
    $("#modal_tak").modal('show');

    postText.val('');
};

var first = 0;

postListRef.orderByChild("godkendt").equalTo(1).on('value', function(snapshot) {
    $(".cards").html("");
    snapshot.forEach(function(childSnapshot) {
        var childKey = childSnapshot.key;
        var childData = childSnapshot.val();

        $(".cards").append("<div class='card'>" + childData.post + "</div>");
        update();
        if(first == 0){
            $(".card").shuffle();
        }
    });
});
