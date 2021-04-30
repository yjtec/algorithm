class yj_common {
    constructor(props) {
    }

    getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return(false);
    }
    getCenter(pA, pB, offset = [0, 0, 0]) {
        var pC = [pA[0], -pA[1], pA[2]];
        var pD = [pB[0], -pB[1], pB[2]];
        return [(pA[0] + pC[0] + pB[0] + pD[0]) / 4 + offset[0], (pA[1] + pC[1] + pB[1] + pD[1]) / 4 + offset[1], (pA[2] + pC[2] + pB[2] + pD[2]) / 4 + offset[2]];
    }
    getPointsCenter(points, offset = [0, 0, 0]) {
        var center = [0, 0, 0];
        for (var i = 0; i < points.length; i++) {
            center[0] += points[i][0] / points.length;
            center[1] += points[i][1] / points.length;
            center[2] += points[i][2] / points.length;
        }
        return [center[0] + offset[0], center[1] + offset[1], center[2] + offset[2]];
    }
    /**
     * 空间内已知两点坐标求距离
     * @param {type} pA
     * @param {type} pB
     * @returns {Number}
     */
    disXYZ(pA, pB) {
        return Math.sqrt(Math.pow(pB[0] - pA[0], 2) + Math.pow(pB[1] - pA[1], 2) + Math.pow(pB[2] - pA[2], 2));
    }
    /**
     * 平面内已知两点坐标求长度
     * @param {type} pA
     * @param {type} pB
     * @returns {Number}
     */
    disXY(pA, pB) {
        return Math.sqrt(Math.pow(pB[0] - pA[0], 2) + Math.pow(pB[1] - pA[1], 2));
    }
    /**
     * 平面内已知三点坐标求面积
     * @param {type} pA
     * @param {type} pB
     * @param {type} pC
     * @returns {undefined}
     */
    areaXY(pA, pB, pC) {
        let len_AB = this.disXY(pA, pB);
        let len_AC = this.disXY(pA, pC);
        let len_BC = this.disXY(pC, pB);
        var p = (len_AB + len_AC + len_BC) / 2;
        return Math.sqrt(p * (p - len_AB) * (p - len_AC) * (p - len_BC));
    }
    r2d(rad) {
        return rad * 180 / Math.PI;
    }
    d2r(degree) {
        return degree * Math.PI / 180;
    }
}