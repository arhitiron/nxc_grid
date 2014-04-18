Array.prototype.repeat= function(what, L){
    while(L) this[--L]= what;
    return this;
}

var NXC = window.NXC || {};
NXC.Grid = (function(cls, options, undef){
    'use strict';

    var me = {};

    me.init = function(){
        me.initialized = false;
        me.options = {
            debug: false,
            mainCls : "#gridresize",
            sizes: {
                tablet: 640,
                desktop: 960
            },
            columns: {
                desktop: 6,
                tablet: 4,
                mobile: 2
            },
            ySize: "data-sizey",
            xSize: "data-sizex",
            row: "data-row",
            col: "data-col",
            cellCls: ".gridresize__cell",
            cellPathCls: "#gridresize .gridresize__cell",
            mobile: "mobile",
            tablet: "tablet",
            desktop: "desktop"
        };

        if (options !== undef) $.extend(true, me.options, options);
        me.firstLoad = true;
        me.matrixCols = 0;
        me.matrixRows = 0;

        if (cls !== undef) {
            var o = me.options;
            o.mainCls = cls;
            o.cellPathCls = cls+" "+o.cellCls;
        }
        me.main = $(me.options.mainCls);
        
        me.initMatrix();
    };

    me.initMatrix = function() {
        me.matrix = [];
        var o = me.options,
            cells = me.getCells(),
            cols = 0,
            rows = 0;

        if (!cells.length || !me.matrixRows) return;
        
        me.fillBaseMatrix(me.matrixRows, me.matrixCols);

        if (!me.matrix.length) return;
        me.initialized = true;

    };

    me.clearMatrix = function(maxColumns) {
        if (!me.initialized || !me.matrix.length) return;
        me.matrix = [];
        if (maxColumns === undef) maxColumns = me.options.columns.desktop;
        me.matrixCols = maxColumns;
        me.fillBaseMatrix(me.matrixRows, maxColumns);
    };

    me.fillBaseMatrix = function(rows, cols) {
        while(rows--) {
            me.matrix.push([].repeat(false, cols));
        }
    };

    me.getCells = function() {
        if (me.cells === undef || !me.cells.length) {
            me.cells = [];
            var o = me.options,
                cells = $(o.cellPathCls);

            var i = 0;
            while(i < cells.length) {

                var $cell = $(cells[i]), cell = {};
                cell.row = parseInt($cell[0].getAttribute(o.row),10);
                cell.col = parseInt($cell[0].getAttribute(o.col),10);
                cell.xSize = parseInt($cell[0].getAttribute(o.xSize),10);
                cell.ySize = parseInt($cell[0].getAttribute(o.ySize),10);
                cell.obj = $cell;

                me.matrixCols += cell.xSize;
                me.matrixRows += cell.ySize;

                me.cells.push(cell);

                ++i;
            }

            me.sortCells();
        }
        return me.cells;
    };

    me.sortCells = function() {
        var temp = [[]];
        
        var count = 0;
        while(count < me.cells.length){
            var cell = me.cells[count];
            if (temp[cell.row] === undef) temp[cell.row] = [];
            temp[cell.row][cell.col] = cell;
            ++count;
        }
        if (!temp.length) return;
        me.cells = [];
        for(var i = 0, l = temp.length; i < l; ++i) {
            if(temp[i] == undef) continue;
            if (!temp[i].length) continue;
            for (var j = 0, d = temp[i].length; j < d; ++j) {
                if (temp[i][j] === undef) continue;
                me.cells.push(temp[i][j]);
            }
        }
    };

    me.printMatrix = function() {
        for(var i = 0, l = me.matrix.length; i < l; ++i) {
            var s = "<"+i+"> ";
            for(var j = 0, c = me.matrixCols; j < c; ++j) {
                s += "["+me.matrix[i][j]+"]";
            }
            console.log(s);
        }
    };

    me.gridResize = function() {
        if (!me.initialized) return;
        var o = me.options,
            type = me.getScreenType();
        switch(type){
            case o.tablet: 
                me.refineTabletGrid();
                break;
            case o.mobile: 
                me.refineMobileGrid();
                break;
            default:
            case o.desktop: 
                me.refineDesktopGrid();
                break;
        }
    };

    me.refineDesktopGrid = function(){
        me.refineGrid(me.options.columns.desktop);
    };

    me.refineTabletGrid = function(){
        me.refineGrid(me.options.columns.tablet);
    };

    me.refineMobileGrid = function(){
        me.refineGrid(me.options.columns.mobile);
    };

    me.applyCell = function(id, cell, xStart, yStart) {
        if (xStart !== undef && yStart !== undef){
            if (yStart >= me.matrix.length || xStart >= me.matrix[yStart].length) {
                return false;
            }
        }

        var changeCellCoordinates = function(y, x, id, cell, checkZero, i, j) {
            if (checkZero !== undef && checkZero == true) {
                if (i == 0 && j == 0) {
                    me.setCellCoord(cell, x+1, y+1);
                }
                me.matrix[y+i][x+j] = id;
            } else {
                me.setCellCoord(cell, x+1, y+1);
                me.matrix[y][x] = id;
            }
        },
        iterateCellSize = function(id, cell, x, y, checkEmptyCell){
            for (var i = 0, il = cell.ySize; i < il; ++i) {
                for (var j = 0, jl = cell.xSize; j < jl; ++j) {
                    if (checkEmptyCell == true) {
                        if (y+i >= me.matrix.length || x+j >= me.matrix[y+i].length || me.matrix[y+i][x+j] !== false) {
                            return false;
                        }
                    } else {
                        changeCellCoordinates(y, x, id, cell, true, i, j);
                    }
                }
            }
            return true;
        };

        for(var y = (yStart === undef) ? 0 : yStart, ly = me.matrix.length; y < ly; ++y) {

            for(var x = (xStart === undef) ? 0 : xStart, lx = me.matrix[y].length; x < lx; ++x) {

                if (cell.xSize == 1 && cell.ySize == 1) {

                    if (me.matrix[y][x] !== false) {
                        return me.applyCell(id, cell, (x >= me.matrix[y].length-1) ? 0 : x+1, (x >= me.matrix[y].length-1) ? y+1 : y ), true;
                    } else {
                        changeCellCoordinates(y, x, id, cell);
                        return true;
                    }

                } else if (cell.xSize > 1 || cell.ySize > 1){
                    if (!iterateCellSize(id, cell, x, y, true)) {
                        return me.applyCell(id, cell, (x >= me.matrix[y].length-1) ? 0 : x+1, (x >= me.matrix[y].length-1) ? y+1 : y ), true;
                    } else {
                        return iterateCellSize(id, cell, x, y, false);
                    }
                    return false;
                }

            }

        }
        return false;
    }

    me.setCellCoord = function(cell, x, y) {
        var o = me.options;
        cell.row = y;
        cell.col = x;
        cell.obj[0].setAttribute(o.row, y);
        cell.obj[0].setAttribute(o.col, x);
    };

    me.refineGrid = function(maxColumns, startRow){
        me.clearMatrix(maxColumns);
        var gridMaxHeight = 0,
            o = me.options,
            i = 0;
        while(i < me.cells.length) {
            var cell = me.cells[i];
            me.applyCell(i, cell);
            var f = parseFloat(cell.obj[0].getAttribute(o.row));
            if(gridMaxHeight < f) {
                gridMaxHeight = f;
            }
            ++i;
        }

        if(gridMaxHeight > 1) {
            me.main[0].setAttribute(o.ySize, gridMaxHeight+1);
        } else {
            me.main[0].setAttribute(o.ySize, ++gridMaxHeight);
        }
        
        if (me.firstLoad) {
            me.main.fadeIn();
            me.firstLoad = false;
        }
        if (me.options.debug) me.printMatrix();
    };

    me.getScreenType = function() {
        var o = me.options,
            type = o.desktop,
            width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        if(width < o.sizes.desktop && width >= o.sizes.tablet){
            type = o.tablet;
        } else if(width < o.sizes.tablet) {
            type = o.mobile;
        }
        return type;
    };

    me.self = function() {
        return {
            resize: me.gridResize
        }
    };

    me.init();

    return me.self();
})("#gridresize", {debug: false});