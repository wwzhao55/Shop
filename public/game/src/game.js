var current = 0;
var GameLayer = cc.Layer.extend({
    balloon : null,
    currentCount : null,
    maxLabel : null,
    currentNumber : 0,
    selectedLevel : 0.1,
    go : null,
    goListener : null,
    times : 1,
    pumpAllow : true,
    balloonAllow : true,
    ctor : function () {
        //////////////////////////////
        // 1. super init first
        this._super();
        var size = cc.winSize;
        var bg = new cc.Sprite(res.gameBg);
        bg.attr({
            x : size.width/2,
            y : size.height/2
        });
        this.addChild(bg, 1);

        // Slider
        var slider = new ccui.Slider();
        slider.setTouchEnabled(true);
        slider.loadBarTexture("slider.png", ccui.Widget.PLIST_TEXTURE);
        slider.loadProgressBarTexture("slider.png", ccui.Widget.PLIST_TEXTURE);
        slider.loadSlidBallTextures("arrow.png", "arrow.png", "arrow.png", ccui.Widget.PLIST_TEXTURE);
        slider.setPercent(25);
        slider.attr({
            x : 120,
            y : 310,
            rotation : 90
        });
        this.addChild(slider, 2);
        slider.addEventListener(this.changeValue, this);

        // Panel
        var count = new cc.Sprite("#count.png");
        count.attr({
            x : 117,
            y : size.height-145
        });
        this.maxLabel = new cc.LabelTTF(max.toString());
        this.maxLabel.attr({
            x : 113,
            y : 167
        });
        this.maxLabel.setFontSize(30);
        this.maxLabel.setColor(cc.color(255,225,119,255));
        this.maxLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
        this.currentCount = new cc.LabelTTF("0");
        this.currentCount.attr({
            x : 113,
            y : 57
        });
        this.currentCount.setFontSize(30);
        this.currentCount.setColor(cc.color(255,225,119,255));
        this.currentCount.textAlign = cc.TEXT_ALIGNMENT_CENTER;
        count.addChild(this.maxLabel);
        count.addChild(this.currentCount);
        this.addChild(count, 2);

        // Balloon
        cc.spriteFrameCache.addSpriteFrames(res.balloon_plist);
        this.balloon = new cc.Sprite("#balloon1.png");
        this.balloon.attr({
            x : size.width / 2 + 5,
            y : size.height - 90,
            anchorY : 1
        });
        this.addChild(this.balloon, 2);

        // Pump
        cc.spriteFrameCache.addSpriteFrames(res.sh_plist);
        this.sh = new cc.Sprite("#air1.png");
        this.sh.attr({
            x: size.width / 2,
            y: 35,
            anchorY : 0
        });
        this.addChild(this.sh, 3);
        this.go = new ccui.Button("go.png", "", "", ccui.Widget.PLIST_TEXTURE);
        this.go.attr({
            x: size.width / 2,
            y: 202
        });
        this.addChild(this.go, 4);
        this.go.runAction(cc.repeatForever(cc.sequence(cc.scaleTo(2, 1.2), cc.scaleTo(2, 1.0, 1.0))));
        this.go.addTouchEventListener(this.doPost, this);

        this.soundBtn = new ccui.Button(res.sound);
        this.soundBtn.attr({
            x : size.width - 70,
            y : size.height - 70
        });
        this.backBtn = new ccui.Button(res.back);
        this.backBtn.attr({
            x : size.width - 70,
            y : size.height - 195
        });
        this.addChild(this.soundBtn, 6);
        this.addChild(this.backBtn, 6);
        if (audioSwitch) {
            this.soundBtn.runAction(cc.repeatForever(cc.rotateBy(5, 360)));
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
        this.backBtn.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                cc.director.runScene(new StartScene());
            }
        }, this);

        // TopRefresh
        var topRefresh = new ccui.Button("topRefresh.png", "", "", ccui.Widget.PLIST_TEXTURE);
        topRefresh.attr({
            x: size.width - 130,
            y: 180
        });
        this.addChild(topRefresh, 3);
        topRefresh.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                if (this.currentNumber == 0) {
                    var maskLayer = new cc.Sprite(res.maskLayer);
                    maskLayer.attr({
                        x: size.width / 2,
                        y: size.height / 2
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
                            maskLayer.removeFromParent();
                        }
                    });
                    cc.eventManager.addListener(listener, maskLayer);
                    this.addChild(maskLayer, 6);
                    return;
                }
                if (phone == null)
                    this.postPhone();
                else
                    this.postData();
            }
        }, this);
        if (phone == null) {
            var maskLayer = new cc.Sprite(res.maskLayer);
            maskLayer.attr({
                x: size.width / 2,
                y: size.height / 2
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
                    maskLayer.removeFromParent();
                }
            });
            cc.eventManager.addListener(listener, maskLayer);
            this.addChild(maskLayer, 6);
        }

        return true;
    },
    changeValue : function (sender, type) {
        if (type == ccui.Slider.EVENT_PERCENT_CHANGED) {
            var percent = sender.getPercent();
            if (percent <= 35) {
                this.selectedLevel = 0.1;
            } else if (percent <= 50) {
                this.selectedLevel = 0.5;
            } else if (percent <= 65) {
                this.selectedLevel = 1;
            } else if (percent <= 75) {
                this.selectedLevel = 5;
            } else if (percent <= 88) {
                this.selectedLevel = 10;
            } else {
                this.selectedLevel = 50;
            }
        }
    },
    doPost : function (sender, type) {
        if (type == ccui.Widget.TOUCH_BEGAN) {
            this.go.stopAllActions();
            this.go.setEnabled(false);
            if (totalTimes <= 0) {
                this.needShare();
            } else {
                this.pump();
                this.balloonRun();
                var xhr = cc.loader.getXMLHttpRequest();
                xhr.open("GET", "http://m.youba.ren/game/oauth.php?param={\"cmd\":\"push\",\"score\":" + this.selectedLevel + "}");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && (xhr.status >= 200 && xhr.status <= 207)) {
                        this.pumpAllow = false;
                        this.balloonAllow = false;
                        var breakFlag = JSON.parse(xhr.responseText);
                        if (breakFlag['break'] == 1) {
                            this.fail();
                        } else if (breakFlag['break'] == 0) {
                            this.goRun();
                        } else if (breakFlag['break'] == -1) {
                            this.needShare();
                            todayTimes = 0;
                        }
                    }
                }.bind(this);
                xhr.send();
            }
        }
    },
    pump : function () {
        if (this.pumpAllow) {
            var shAnimFrames = [];
            for (var i = 1; i < 18; i++) {
                var str = "air" + i + ".png";
                var frame = cc.spriteFrameCache.getSpriteFrame(str);
                shAnimFrames.push(frame);                                       //取出plist文件中所有sprite，加入数组
            }
            var shAnimation = new cc.Animation(shAnimFrames, 0.1);                //定义图片播放间隔
            var shAnimationAction = new cc.Animate(shAnimation);
            this.sh.runAction(cc.sequence(cc.spawn(shAnimationAction, cc.callFunc(function () {
                if (audioSwitch) {
                    cc.audioEngine.playEffect(res.pump, false);
                }
            }, this)), cc.callFunc(this.pump, this)));
        } else {
            this.pumpAllow = true;
        }
    },
    balloonRun : function () {
        if (this.balloonAllow) {
            this.balloon.runAction(cc.sequence(cc.rotateTo(2, -15), cc.rotateTo(4, 15), cc.rotateTo(2, 0), cc.callFunc(this.balloonRun, this)));
        } else {
            this.balloonAllow = true;
        }
    },
    goRun : function () {
        this.currentNumber = parseFloat((this.currentNumber + this.selectedLevel).toFixed(1));
        this.currentCount.setString(this.currentNumber);
        this.times = this.times == 9 ? 9 : this.times + 1;
        this.balloon.setSpriteFrame("balloon"+this.times+".png");
        this.go.runAction(cc.repeatForever(cc.sequence(cc.scaleTo(2, 1.2), cc.scaleTo(2, 1.0, 1.0))));
        this.go.setEnabled(true);
    },
    fail : function () {
        todayTimes--;
        totalTimes--;
        var failLayer = new cc.Sprite(res.failLayer);
        failLayer.attr({
            x: cc.winSize.width/2,
            y: cc.winSize.height/2
        });
        this.addChild(failLayer, 7);
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
                return true;
            }
        });
        cc.eventManager.addListener(listener, failLayer);
        var reTry = new ccui.Button("reTry.png", "", "", ccui.Widget.PLIST_TEXTURE);
        reTry.attr({
            x: cc.winSize.width/2,
            y: cc.winSize.height/2 - 200
        });
        reTry.addTouchEventListener(function () {
            failLayer.removeFromParent();
            this.currentNumber = 0;
            this.currentCount.setString("0");
            this.balloon.setSpriteFrame("balloon1.png");
            this.times = 1;
            this.go.runAction(cc.repeatForever(cc.sequence(cc.scaleTo(2, 1.2), cc.scaleTo(2, 1.0, 1.0))));
            this.go.setEnabled(true);
        }, this);
        failLayer.addChild(reTry);
    },
    postPhone : function () {
        var phoneLayer = new cc.Sprite(res.phoneLayer);
        phoneLayer.attr({
            x: cc.winSize.width/2,
            y: cc.winSize.height/2
        });
        this.addChild(phoneLayer, 5);
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
                return true;
            }
        });
        cc.eventManager.addListener(listener, phoneLayer);
        var current = new cc.LabelTTF(this.currentNumber.toString(), "Arial", 40);
        current.attr({
            x: 440,
            y: 601,
            anchorX: 0,
            anchorY: 0.5
        });
        current.setHorizontalAlignment(cc.TEXT_ALIGNMENT_LEFT);
        phoneLayer.addChild(current);
        var conGame = new ccui.Button("continue.png", "", "", ccui.Widget.PLIST_TEXTURE);
        conGame.attr({
            x: cc.winSize.width/2,
            y: 345
        });
        conGame.addTouchEventListener(function () {
            phoneLayer.removeFromParent();
        }, this);
        phoneLayer.addChild(conGame);
        var inputFrame = new cc.Sprite("#inputFrame.png");
        inputFrame.attr({
            x: 252,
            y: 472,
            anchor: 0.5
        });
        phoneLayer.addChild(inputFrame);
        var phoneInput = new cc.EditBox(cc.p(371, 91), new cc.Scale9Sprite());
        phoneInput.setFont("Arial");
        phoneInput.setFontColor(cc.color.BLACK);
        phoneInput.setFontSize(30);
        phoneInput.setPlaceHolder("请输入手机号");
        phoneInput.setPlaceholderFontSize(30);
        phoneInput.setInputMode(cc.EDITBOX_INPUT_MODE_PHONENUMBER);
        phoneInput.setMaxLength(11);
        phoneInput.attr({
            x: 75,
            y: 25,
            anchorX: 0,
            anchorY: 0
        });
        inputFrame.addChild(phoneInput);
        var finish = new ccui.Button("finish.png", "", "", ccui.Widget.PLIST_TEXTURE);
        finish.attr({
            x: 575,
            y: 470
        });
        finish.addTouchEventListener(function (sender, type) {
            if (type == ccui.Widget.TOUCH_BEGAN) {
                conGame.setEnabled(false);
                finish.setEnabled(false);
                var xhr = cc.loader.getXMLHttpRequest();
                xhr.open("GET", "http://m.youba.ren/game/oauth.php?param={\"cmd\":\"register\",\"phone\":\"" + phoneInput.getString() + "\"}");
                xhr.send();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status >= 200 && xhr.status <= 207) {
                            var re = JSON.parse(xhr.responseText);
                            if (re['register_info'] == 1) {
                                phone = phoneInput.getString();
                                phoneLayer.removeFromParent();
                                this.postData();
                                return;
                            }
                        }
                        alert("注册失败!");
                        conGame.setEnabled(true);
                        finish.setEnabled(true);
                    }
                }.bind(this);
            }
        }, this);
        phoneLayer.addChild(finish);
    },
    postData : function () {
        todayTimes--;
        totalTimes--;
        var xhr = cc.loader.getXMLHttpRequest();
        xhr.open("GET", "http://m.youba.ren/game/oauth.php?param={\"cmd\":\"harvest\"}");
        xhr.send();
        var size = cc.winSize;
        max = this.currentNumber > max ? this.currentNumber : max;
        this.maxLabel.setString(max.toString());
        this.currentNumber = 0;
        this.currentCount.setString("0");
        this.balloon.setSpriteFrame("balloon1.png");
        this.times = 1;
        var queryLayer = new cc.Sprite(res.queryLayer);
        queryLayer.attr({
            x: size.width / 2,
            y: size.height / 2
        });
        var maxLabel = new cc.LabelTTF(max.toString());
        maxLabel.setFontSize(50);
        maxLabel.setColor(cc.color(255, 218, 89, 255));
        maxLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
        maxLabel.attr({
            x: size.width / 2 + 125,
            y: size.height / 2 + 110
        });
        queryLayer.addChild(maxLabel);
        var totalLabel = new cc.LabelTTF(totalTimes.toString());
        totalLabel.setFontSize(50);
        totalLabel.setColor(cc.color(255, 218, 89, 255));
        totalLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
        totalLabel.attr({
            x: size.width / 2 + 125,
            y: size.height / 2 - 60
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
    },
    needShare : function () {
        this.go.stopAllActions();
        this.go.setEnabled(false);
        var shareLayer = new cc.Sprite(res.shareLayer);
        shareLayer.attr({
            x : cc.winSize.width/2,
            y : cc.winSize.height/2
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
                this.go.runAction(cc.repeatForever(cc.sequence(cc.scaleTo(2, 1.2), cc.scaleTo(2, 1.0, 1.0))));
                this.go.setEnabled(true);
                shareLayer.removeFromParent();
            }.bind(this)
        });
        cc.eventManager.addListener(listener, shareLayer);
        this.addChild(shareLayer, 7, 1);
    }
});

var GameScene = cc.Scene.extend({
    onEnter:function () {
        this._super();
        var layer = new GameLayer();
        this.addChild(layer);
    }
});
