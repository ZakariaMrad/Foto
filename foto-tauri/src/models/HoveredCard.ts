import { th } from "vuetify/locale";

export default class HoveredCard {
    top: boolean = false;
    bottom: boolean = false;
    left: boolean = false;
    right: boolean = false;
    topLeft: boolean = false;
    topRight: boolean = false;
    bottomLeft: boolean = false;
    bottomRight: boolean = false;
    center: boolean = false;
    
    constructor(top: boolean, bottom: boolean, left: boolean, right: boolean, topLeft: boolean, topRight: boolean, bottomLeft: boolean, bottomRight: boolean, center: boolean) {
        this.top = top;
        this.bottom = bottom;
        this.left = left;
        this.right = right;
        this.topLeft = topLeft;
        this.topRight = topRight;
        this.bottomLeft = bottomLeft;
        this.bottomRight = bottomRight;
        this.center = center;
    }
    
    static emptyHoveredCard(): HoveredCard  {
        return new HoveredCard(false, false, false, false, false, false, false, false, false);
    }
    static onlyTop(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setTop();
        return hc;
    }
    static onlyBottom(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setBottom();
        return hc;
    }
    static onlyLeft(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setLeft();
        return hc;
    }
    static onlyRight(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setRight();
        return hc;
    }
    static onlyTopLeft(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setTopLeft();
        return hc;
    }
    static onlyTopRight(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setTopRight();
        return hc;
    }
    static onlyBottomLeft(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setBottomLeft();
        return hc;
    }
    static onlyBottomRight(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setBottomRight();
        return hc;
    }
    static onlyCenter(): HoveredCard {
        let hc = this.emptyHoveredCard();
        hc.setCenter();
        return hc;
    }
    


    private setTop() {
        this.top = true;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    private setBottom() {
        this.top = false;
        this.bottom = true;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    setLeft() {
        this.top = false;
        this.bottom = false;
        this.left = true;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    setRight() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = true;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    setTopLeft() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = true;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    private setTopRight() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = true;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = false;
    }
    private setBottomLeft() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = true;
        this.bottomRight = false;
        this.center = false;
    }
    private setBottomRight() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = true;
        this.center = false;
    }
    private setCenter() {
        this.top = false;
        this.bottom = false;
        this.left = false;
        this.right = false;
        this.topLeft = false;
        this.topRight = false;
        this.bottomLeft = false;
        this.bottomRight = false;
        this.center = true;
    }

}