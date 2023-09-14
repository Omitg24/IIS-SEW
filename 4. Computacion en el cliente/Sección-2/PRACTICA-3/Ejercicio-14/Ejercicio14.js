class Player {
    constructor() {
        $("input[type=\"file\"]").val(null);
        this.selectPlaylist = document.getElementsByTagName("select")[0];
        this.selectFavourites = document.getElementsByTagName("select")[1];
        this.playState = false;
        this.audio = new Audio();
        this.paths = new Array();
        this.favouritePaths = new Array();
        this.selectedIndex = -1;
        
        this.createAndLoadDatabase();
    }

    createAndLoadDatabase() {
        var openRequest = window.indexedDB.open("BatplayerFavourites", 3);
        openRequest.onerror = function() {
            $("section").append("<section><h2>Ha ocurrido un error</h2></section>");
        }
        openRequest.onupgradeneeded = function() {
            this.db = openRequest.result;            
            var store = this.db.createObjectStore("Favourites", {keyPath: "songName"});
            var index = store.createIndex("songUrl", "url", {unique:true});
        };
        openRequest.onsuccess = function() {
            this.db = openRequest.result;
            var tx = this.db.transaction("Favourites", "readwrite");
            var store = tx.objectStore("Favourites");
            var index = store.index("songUrl");
            var favouriteObjects = store.getAll();
            favouriteObjects.onsuccess = function() {
                var favourites = favouriteObjects.result;
                for (var i = 0; i < favourites.length; i++) {
                    var name = favourites[i].songName;
                    var url = favourites[i].songUrl;
                    $("select:last").append("<option>" + name + "</option>");
                    this.favouritePaths.push(url);
                }
            }.bind(this);
        }.bind(this);
    }

    addToPlayList() {
        $("select:first option").remove();
        this.paths = new Array();
        this.archivos = document.querySelector("input[type=file]").files;        
        for (var i = 0; i < this.archivos.length; i++) {
            var name = this.archivos[i].name;        
            var size = Math.round((this.archivos[i].size / 1048576) * 100) / 100;
            var song = name.replaceAll("_", " ") + " | " + size + "MB";
            $("select:first").append("<option>" + song + "</option>");
            this.paths.push("multimedia/songs/" + name);
        }
    }

    toggleToFavourite() {
        if (this.selectPlaylist.selectedIndex != -1) {
            var selected = this.selectPlaylist.options[this.selectedIndex].innerText;
            var selectedUrl = this.paths[this.selectedIndex];
            if (!this.checkIfIsFavourite(selected)) {
                const favourite = {songName: selected, songUrl: selectedUrl};
                const transaction = this.db.transaction(["Favourites"], "readwrite");            
                const store = transaction.objectStore("Favourites");
                const added = store.add(favourite);
                this.favouritePaths.push(selectedUrl);
                
                $("select:last").append("<option>" + selected + "</option>");
                this.updateButton("♥", "❤");                   
            }
        } else if (this.selectFavourites.selectedIndex != -1) {
            const favourite = this.selectFavourites.options[this.selectedIndex].innerText;
            const transaction = this.db.transaction(["Favourites"], "readwrite");            
            const store = transaction.objectStore("Favourites");
            const deleted = store.delete(favourite);

            var index = this.getIndexFromList(this.selectFavourites, favourite);
            this.favouritePaths.splice(index, 1);
            this.selectFavourites.options.remove(index);            
            
            this.selectFavourites.selectedIndex += 1;
            this.selectedIndex = this.selectFavourites.selectedIndex;
        }        
    }

    isFavourite() {
        if (this.selectFavourites.options.length > 0){
            var selected = this.selectPlaylist.options[this.selectedIndex].innerText;
            if (this.checkIfIsFavourite(selected)) {
                this.updateButton("♥", "❤");
            } else {
                this.updateButton("❤", "♥");
            }
        }        
    }

    updateIndex() {  
        if (this.selectPlaylist.selectedIndex != -1) {
            this.selectedIndex = this.selectPlaylist.selectedIndex;
        }
        if (this.selectFavourites.selectedIndex != -1) {
            this.selectedIndex = this.selectFavourites.selectedIndex;        
        }
        if (this.playState) {
            this.updateButton("||", "▶");
            this.playState = false;
        }
    }

    updatePlaylist() {
        this.selectFavourites.selectedIndex=-1;
    }

    updateFavourites() {
        this.selectPlaylist.selectedIndex=-1;
        this.updateButton("♥", "❤");
    }

    playOrPause() {
        if (this.selectPlaylist.options.length>0 || this.selectFavourites.options.length>0) {
            if (this.playState) {
                document.querySelector("input[type=\"button\"][value=\"||\"]").value="▶";
                this.pause();
            } else {
                document.querySelector("input[type=\"button\"][value=\"▶\"]").value="||";            
                this.play();
            }        
        }
    }

    play() {        
        $("p").remove();
        this.checkIfAudioIsPlaying();
        this.updateButton("🕨", "🕪");
        if (this.selectedIndex == -1) {
            this.selectPlaylist.selectedIndex = 0;
            this.selectFavourites.selectedIndex = 0;
            this.selectedIndex = 0;
        }
        if (this.selectPlaylist.selectedIndex != -1) {
            this.audio = new Audio(this.paths[this.selectedIndex]);
            $("main").append("<p>Playing... " + this.selectPlaylist.options[this.selectedIndex].innerText + "</p>");
        } else if (this.selectFavourites.selectedIndex != -1) {
            this.audio = new Audio(this.favouritePaths[this.selectedIndex]);
            $("main").append("<p>Playing... " + this.selectFavourites.options[this.selectedIndex].innerText + "</p>");
        }        
        this.audio.play();
        this.audio.onended = (event) => {
            this.next();
        };
        this.playState = true;
    }
    
    pause() {
        this.audio.pause();
        this.playState = false;
    }

    stop() {
        if (this.playState) {
            document.querySelector("input[type=\"button\"][value=\"||\"]").value="▶";
            this.playState = false;
        }
        $("p").remove();
        this.audio.pause();
        this.audio.currentTime = 0;
    }

    previous() {
        if (this.selectPlaylist.selectedIndex != -1) {
            this.selectPlaylist.selectedIndex -= 1;
            this.selectedIndex = this.selectPlaylist.selectedIndex;            
        } else if (this.selectFavourites.selectedIndex != -1) {        
            this.selectFavourites.selectedIndex -= 1;
            this.selectedIndex = this.selectFavourites.selectedIndex;
        }
        this.play();
    }

    next() {
        if (this.selectPlaylist.selectedIndex != -1) {
            this.selectPlaylist.selectedIndex += 1;
            this.selectedIndex = this.selectPlaylist.selectedIndex;            
        } else if (this.selectFavourites.selectedIndex != -1) {        
            this.selectFavourites.selectedIndex += 1;
            this.selectedIndex = this.selectFavourites.selectedIndex;
        }
        this.play();
    }

    muteOrUnmute() {
        if (this.audio.muted) {
            this.audio.muted=false;
            document.querySelector("input[type=\"button\"][value=\"🕨\"]").value="🕪";
        } else {
            this.audio.muted=true;
            document.querySelector("input[type=\"button\"][value=\"🕪\"]").value="🕨";
        }
    }

    clearFavourites() {
        const transaction = this.db.transaction(["Favourites"], "readwrite");            
        const store = transaction.objectStore("Favourites");
        const cleared = store.clear();
        $("select:last option").remove();
    }

    checkIfIsFavourite(selected) {
        for (var i = 0; i < this.selectFavourites.options.length; i++) {
            if (this.selectFavourites.options[i].innerText == selected) {
                return true;
            }
        }
        return false;
    }

    checkIfAudioIsPlaying() {
        if (this.audio.played) {
            this.audio.pause();
            this.audio.currentTime = 0;
        }
    }

    updateButton(icon1, icon2) {
        if (document.querySelector("input[type=\"button\"][value=\""+ icon1 + "\"]") != null) {
            document.querySelector("input[type=\"button\"][value=\""+ icon1 + "\"]").value=icon2;
        }
    }

    getIndexFromList(list, element) {
        for (var i=0; i<list.length; i++) {
            if (list[i].childNodes[0].nodeValue == element) {                
                return i;
            }
        }
        return -1;
    }
}
var player = new Player();

document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        if (player.playState) {
            player.audio.pause();
        }        
    } else {
        if (player.playState) {
            player.audio.play();
        }
    }
});

document.addEventListener('keydown', function(e) {
    switch (e.key.toLowerCase()) {
        case "a":
            player.toggleToFavourite();
            break;
        case "c":
            player.clearFavourites();
            break;
        case "m":
            player.muteOrUnmute();
            break;
        case "p":
            player.previous();
            break;
        case "n":
            player.next();
            break;
        case "f":
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
            break;
        case "escape":
            if (document.fullscreenElement) {
                document.exitFullscreen();
            }
            break;
        case " ":
            player.stop();
            e.preventDefault();
            break;
        case "enter":
            player.playOrPause();
            e.preventDefault();
            break;
    }
});