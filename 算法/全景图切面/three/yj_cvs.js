class yj_cvs {
    COLOR = "#1890ff";
    LINEWIDTH = 2;
    DOTRADIUS = 4;
    constructor(props) {
        this.canvas = props.canvas || null;//画图用的
    }
    drawText(text, x, y) {
        if (!this.canvas) {
            return;
        }
        this.canvas.font = '10px Arial';
        this.canvas.fillStyle = '#FF0000';
        this.canvas.fillText(text, x, y);
    }
    drawBox(x, y, w, color, fill = true) {
        if (!this.canvas) {
            return;
        }
        this.canvas.fillStyle = color;
        if (fill) {//填充方块儿
            this.canvas.fillRect(x, y, w, w);
        } else {//空方块
            this.canvas.rect(x, y, w, w);
            this.canvas.stroke();
        }
        return;
    }
    drawDot(x, y) {
        this.canvas.beginPath()
        this.canvas.fillStyle = '#FFFF00';
        this.canvas.arc(x, y, this.DOTRADIUS, 0, 2 * Math.PI);
        this.canvas.fill()
    }

    drawLine(sx, sy, ex, ey) {
        this.canvas.beginPath()
        this.canvas.moveTo(sx, sy);
        this.canvas.lineTo(ex, ey);
        this.canvas.stroke();
    }
    /**
     * 画一个多边形
     * @param {type} points
     * @param {type} offset
     * @returns {undefined}
     */
    drawPolygon(pano, points, offset) {
        this.drawDot(300 + offset[0] * 10, 300 + offset[2] * 10);
        this.drawText(pano, 300 + offset[0] * 10, 300 + offset[2] * 10);
        for (var i = 0; i < points.length; i++) {
            let next = (i + 1 == points.length ? 0 : i + 1);
            this.drawText(i, (points[i][0] + offset[0]) * 10 + 300, (points[i][2] + offset[2]) * 10 + 300);//画顶点的索引号
            this.drawLine(
                    (points[i][0] + offset[0]) * 10 + 300,
                    (points[i][2] + offset[2]) * 10 + 300,
                    (points[next][0] + offset[0]) * 10 + 300,
                    (points[next][2] + offset[2]) * 10 + 300
                    );
        }
    }
}