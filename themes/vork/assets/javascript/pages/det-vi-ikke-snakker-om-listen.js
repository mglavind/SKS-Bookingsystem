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

postListRef.orderByChild("godkendt").equalTo(0).on('value', function(snapshot) {
    $("#toRead").html("");
    snapshot.forEach(function(childSnapshot) {
        var childKey = childSnapshot.key;
        var childData = childSnapshot.val();

        $("#toRead").append("<tr><td>"+childData.post+"</td><td><div class='btn-group' data-post-id='"+childKey+"'><button type='button' class='btn btn-danger slet'>Slet</button><button type='button' class='btn btn-success godkend'>Godkend</button></div></td></tr>");

    });
});

postListRef.orderByChild("godkendt").startAt(1).on('value', function(snapshot) {
    $("#isRead").html("");
    snapshot.forEach(function(childSnapshot) {
        var childKey = childSnapshot.key;
        var childData = childSnapshot.val();

        $("#isRead").append("<tr><td>"+childData.post+"</td><td>"+(childData.godkendt==1 ? "<span class='text-success'>Godkendt</span>" :(childData.godkendt==2 ? "<span class='text-danger'>Slettet</span>" : ""))+"<br><div class='btn-group' data-post-id='"+childKey+"'><button type='button' class='btn btn-primary nulstil'>Nulstil</button></div></td></tr>");

    });
});

$(document).on('click', ".godkend", function() {
    postListRef.child($(this).parent().data('post-id')).child('godkendt').set(1);
});
$(document).on('click', ".slet", function() {
    postListRef.child($(this).parent().data('post-id')).child('godkendt').set(2);
});
$(document).on('click', ".nulstil", function() {
    postListRef.child($(this).parent().data('post-id')).child('godkendt').set(0);
});