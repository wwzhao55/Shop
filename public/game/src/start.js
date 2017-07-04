var audioSwitch = true;

var StartLayer = cc.Layer.extend({
    soundBtn : null,
    ctor:function () {
        //////////////////////////////
        // 1. super init first
        this._super();

        /////////////////////////////
        // 2. add a menu item with "X" image, which is clicked to quit the program
        //    you may modify it.
        // ask the window size
        var size = cc.winSize;

        var cloud = new cc.Sprite(res.cloud);
        cloud.attr({
            x : size.width/2,
            y : size.height/2
        });
        this.addChild(cloud, 1);
        var box = new cc.Sprite(res.box);
        box.attr({
            x : size.width/2,
            y : size.height - 522
        });
        this.addChild(box, 2);
        box.runAction(cc.repeatForever(cc.sequence(cc.moveBy(2, 0, -20), cc.moveBy(2, 0, 20))));
        var frame = new cc.Sprite(res.frame);
        frame.attr({
            x : size.width/2,
            y : size.height/2
        });
        this.addChild(frame, 3);
        var title = new cc.Sprite("#title.png");
        title.attr({
            x : size.width/2,
            y : 908
        });
        this.addChild(title, 4);
        var start = new ccui.Button("start.png", "", "", ccui.Widget.PLIST_TEXTURE);
        start.attr({
            x : size.width/2,
            y : 120
        });
        this.addChild(start, 4);
        var rule = new ccui.Button("rule.png", "", "", ccui.Widget.PLIST_TEXTURE);
        rule.attr({
            x : 140,
            y : 120
        });
        var query = new ccui.Button("query.png", "", "", ccui.Widget.PLIST_TEXTURE);
        query.attr({
            x : size.width-140,
            y : 120
        });
        this.addChild(rule, 4);
        this.addChild(query, 4);

        this.soundBtn = new ccui.Button(res.sound);
        this.soundBtn.attr({
            x : size.width - 70,
            y : size.height - 70
        });
        this.addChild(this.soundBtn, 6);
        if (audioSwitch) {
            this.soundBtn.runAction(cc.repeatForever(cc.rotateBy(5, 360)));
            if (!cc.audioEngine.isMusicPlaying()) {
                cc.audioEngine.playMusic(res.bg, true);
            }
        }
        this.soundBtn.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                if (audioSwitch) {
                    audioSwitch = false;
                    this.soundBtn.stopAllActions();
                    this.soundBtn.setRotation(0);
                    cc.audioEngine.stopMusic();
                } else {
                    audioSwitch = true;
                    cc.audioEngine.playMusic(res.bg, true);
                    this.soundBtn.runAction(cc.repeatForever(cc.rotateBy(5, 360)));
                }
            }
        }, this);

        start.addTouchEventListener(function () {
            cc.director.runScene(new GameScene());
        }, this);
        rule.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                var ruleLayer = new cc.Sprite(res.ruleLayer);
                ruleLayer.attr({
                    x : size.width/2,
                    y : size.height/2
                });
                var listener = cc.EventListener.create({
                    event: cc.EventListener.TOUCH_ONE_BY_ONE,
                    swallowTouches: true,
                    onTouchBegan: function () {
                        return true;
                    },
                    onTouchMoved: function () {
                        return true;
                    },
                    onTouchEnded: function () {
                        ruleLayer.removeFromParent();
                    }
                });
                cc.eventManager.addListener(listener, ruleLayer);
                this.addChild(ruleLayer, 7);
            }
        }, this);
        query.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                var queryLayer = new cc.Sprite(res.queryLayer);
                queryLayer.attr({
                    x : size.width/2,
                    y : size.height/2
                });
                var maxLabel = new cc.LabelTTF(max.toString());
                maxLabel.setFontSize(50);
                maxLabel.setColor(cc.color(255,218,89,255));
                maxLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
                maxLabel.attr({
                    x : size.width/2+125,
                    y : size.height/2+110
                });
                queryLayer.addChild(maxLabel);
                var totalLabel = new cc.LabelTTF(totalTimes.toString());
                totalLabel.setFontSize(50);
                totalLabel.setColor(cc.color(255,218,89,255));
                totalLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
                totalLabel.attr({
                    x : size.width/2+125,
                    y : size.height/2-60
                });
                queryLayer.addChild(totalLabel);
                var listener = cc.EventListener.create({
                    event: cc.EventListener.TOUCH_ONE_BY_ONE,
                    swallowTouches: true,
                    onTouchBegan: function () {
                        return true;
                    },
                    onTouchMoved: function () {
                        return true;
                    },
                    onTouchEnded: function () {
                        queryLayer.removeFromParent();
                    }
                });
                cc.eventManager.addListener(listener, queryLayer);
                this.addChild(queryLayer, 7);
            }
        }, this);
        return true;
    }
});

var StartScene = cc.Scene.extend({
    onEnter:function () {
        this._super();
        var layer = new StartLayer();
        this.addChild(layer);
    }
});
