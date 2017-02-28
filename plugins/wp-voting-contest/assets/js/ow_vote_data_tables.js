/*!
 *Ohio webtech voting data tables 1.10.4
 */
(function(Da, P, l) {
    var O = function(g) {
        function V(a) {
            var b, c, e = {};
            g.each(a, function(d) {
                if ((b = d.match(/^([^A-Z]+?)([A-Z])/)) && -1 !== "a aa ai ao as b fn i m o s ".indexOf(b[1] + " ")) c = d.replace(b[0], b[2].toLowerCase()), e[c] = d, "o" === b[1] && V(a[d])
            });
            a._hungarianMap = e
        }

        function G(a, b, c) {
            a._hungarianMap || V(a);
            var e;
            g.each(b, function(d) {
                e = a._hungarianMap[d];
                if (e !== l && (c || b[e] === l)) "o" === e.charAt(0) ? (b[e] || (b[e] = {}), g.extend(!0, b[e], b[d]), G(a[e], b[e], c)) : b[e] = b[d]
            })
        }

        function O(a) {
            var b = p.defaults.oLanguage,
                c = a.sZeroRecords;
            !a.sEmptyTable && (c && "No data available in table" === b.sEmptyTable) && D(a, a, "sZeroRecords", "sEmptyTable");
            !a.sLoadingRecords && (c && "Loading..." === b.sLoadingRecords) && D(a, a, "sZeroRecords", "sLoadingRecords");
            a.sInfoThousands && (a.sThousands = a.sInfoThousands);
            (a = a.sDecimal) && cb(a)
        }

        function db(a) {
            z(a, "ordering", "bSort");
            z(a, "orderMulti", "bSortMulti");
            z(a, "orderClasses", "bSortClasses");
            z(a, "orderCellsTop", "bSortCellsTop");
            z(a, "order", "aaSorting");
            z(a, "orderFixed", "aaSortingFixed");
            z(a, "paging", "bPaginate");
            z(a, "pagingType", "sPaginationType");
            z(a, "pageLength", "iDisplayLength");
            z(a, "searching", "bFilter");
            if (a = a.aoSearchCols)
                for (var b = 0, c = a.length; b < c; b++) a[b] && G(p.models.oSearch, a[b])
        }

        function eb(a) {
            z(a, "orderable", "bSortable");
            z(a, "orderData", "aDataSort");
            z(a, "orderSequence", "asSorting");
            z(a, "orderDataType", "sortDataType")
        }

        function fb(a) {
            var a = a.oBrowser,
                b = g("<div/>").css({
                    position: "absolute",
                    top: 0,
                    left: 0,
                    height: 1,
                    width: 1,
                    overflow: "hidden"
                }).append(g("<div/>").css({
                    position: "absolute",
                    top: 1,
                    left: 1,
                    width: 100,
                    overflow: "scroll"
                }).append(g('<div class="test"/>').css({
                    width: "100%",
                    height: 10
                }))).appendTo("body"),
                c = b.find(".test");
            a.bScrollOversize = 100 === c[0].offsetWidth;
            a.bScrollbarLeft = 1 !== c.offset().left;
            b.remove()
        }

        function gb(a, b, c, e, d, f) {
            var h, i = !1;
            c !== l && (h = c, i = !0);
            for (; e !== d;) a.hasOwnProperty(e) && (h = i ? b(h, a[e], e, a) : a[e], i = !0, e += f);
            return h
        }

        function Ea(a, b) {
            var c = p.defaults.column,
                e = a.aoColumns.length,
                c = g.extend({}, p.models.oColumn, c, {
                    nTh: b ? b : P.createElement("th"),
                    sTitle: c.sTitle ? c.sTitle : b ? b.innerHTML : "",
                    aDataSort: c.aDataSort ? c.aDataSort : [e],
                    mData: c.mData ? c.mData : e,
                    idx: e
                });
            a.aoColumns.push(c);
            c = a.aoPreSearchCols;
            c[e] = g.extend({}, p.models.oSearch, c[e]);
            ja(a, e, null)
        }

        function ja(a, b, c) {
            var b = a.aoColumns[b],
                e = a.oClasses,
                d = g(b.nTh);
            if (!b.sWidthOrig) {
                b.sWidthOrig = d.attr("width") || null;
                var f = (d.attr("style") || "").match(/width:\s*(\d+[pxem%]+)/);
                f && (b.sWidthOrig = f[1])
            }
            c !== l && null !== c && (eb(c), G(p.defaults.column, c), c.mDataProp !== l && !c.mData && (c.mData = c.mDataProp), c.sType && (b._sManualType = c.sType), c.className &&
                !c.sClass && (c.sClass = c.className), g.extend(b, c), D(b, c, "sWidth", "sWidthOrig"), "number" === typeof c.iDataSort && (b.aDataSort = [c.iDataSort]), D(b, c, "aDataSort"));
            var h = b.mData,
                i = W(h),
                j = b.mRender ? W(b.mRender) : null,
                c = function(a) {
                    return "string" === typeof a && -1 !== a.indexOf("@")
                };
            b._bAttrSrc = g.isPlainObject(h) && (c(h.sort) || c(h.type) || c(h.filter));
            b.fnGetData = function(a, b, c) {
                var e = i(a, b, l, c);
                return j && b ? j(e, b, a, c) : e
            };
            b.fnSetData = function(a, b, c) {
                return Q(h)(a, b, c)
            };
            "number" !== typeof h && (a._rowReadObject = !0);
            a.oFeatures.bSort ||
                (b.bSortable = !1, d.addClass(e.sSortableNone));
            a = -1 !== g.inArray("asc", b.asSorting);
            c = -1 !== g.inArray("desc", b.asSorting);
            !b.bSortable || !a && !c ? (b.sSortingClass = e.sSortableNone, b.sSortingClassJUI = "") : a && !c ? (b.sSortingClass = e.sSortableAsc, b.sSortingClassJUI = e.sSortJUIAscAllowed) : !a && c ? (b.sSortingClass = e.sSortableDesc, b.sSortingClassJUI = e.sSortJUIDescAllowed) : (b.sSortingClass = e.sSortable, b.sSortingClassJUI = e.sSortJUI)
        }

        function X(a) {
            if (!1 !== a.oFeatures.bAutoWidth) {
                var b = a.aoColumns;
                Fa(a);
                for (var c = 0, e = b.length; c <
                    e; c++) b[c].nTh.style.width = b[c].sWidth
            }
            b = a.oScroll;
            ("" !== b.sY || "" !== b.sX) && Y(a);
            u(a, null, "column-sizing", [a])
        }

        function ka(a, b) {
            var c = Z(a, "bVisible");
            return "number" === typeof c[b] ? c[b] : null
        }

        function $(a, b) {
            var c = Z(a, "bVisible"),
                c = g.inArray(b, c);
            return -1 !== c ? c : null
        }

        function aa(a) {
            return Z(a, "bVisible").length
        }

        function Z(a, b) {
            var c = [];
            g.map(a.aoColumns, function(a, d) {
                a[b] && c.push(d)
            });
            return c
        }

        function Ga(a) {
            var b = a.aoColumns,
                c = a.aoData,
                e = p.ext.type.detect,
                d, f, h, i, j, g, m, o, k;
            d = 0;
            for (f = b.length; d < f; d++)
                if (m =
                    b[d], k = [], !m.sType && m._sManualType) m.sType = m._sManualType;
                else if (!m.sType) {
                h = 0;
                for (i = e.length; h < i; h++) {
                    j = 0;
                    for (g = c.length; j < g; j++) {
                        k[j] === l && (k[j] = v(a, j, d, "type"));
                        o = e[h](k[j], a);
                        if (!o && h !== e.length - 1) break;
                        if ("html" === o) break
                    }
                    if (o) {
                        m.sType = o;
                        break
                    }
                }
                m.sType || (m.sType = "string")
            }
        }

        function hb(a, b, c, e) {
            var d, f, h, i, j, n, m = a.aoColumns;
            if (b)
                for (d = b.length - 1; 0 <= d; d--) {
                    n = b[d];
                    var o = n.targets !== l ? n.targets : n.aTargets;
                    g.isArray(o) || (o = [o]);
                    f = 0;
                    for (h = o.length; f < h; f++)
                        if ("number" === typeof o[f] && 0 <= o[f]) {
                            for (; m.length <=
                                o[f];) Ea(a);
                            e(o[f], n)
                        } else if ("number" === typeof o[f] && 0 > o[f]) e(m.length + o[f], n);
                    else if ("string" === typeof o[f]) {
                        i = 0;
                        for (j = m.length; i < j; i++)("_all" == o[f] || g(m[i].nTh).hasClass(o[f])) && e(i, n)
                    }
                }
            if (c) {
                d = 0;
                for (a = c.length; d < a; d++) e(d, c[d])
            }
        }

        function I(a, b, c, e) {
            var d = a.aoData.length,
                f = g.extend(!0, {}, p.models.oRow, {
                    src: c ? "dom" : "data"
                });
            f._aData = b;
            a.aoData.push(f);
            for (var b = a.aoColumns, f = 0, h = b.length; f < h; f++) c && Ha(a, d, f, v(a, d, f)), b[f].sType = null;
            a.aiDisplayMaster.push(d);
            (c || !a.oFeatures.bDeferRender) && Ia(a,
                d, c, e);
            return d
        }

        function la(a, b) {
            var c;
            b instanceof g || (b = g(b));
            return b.map(function(b, d) {
                c = ma(a, d);
                return I(a, c.data, d, c.cells)
            })
        }

        function v(a, b, c, e) {
            var d = a.iDraw,
                f = a.aoColumns[c],
                h = a.aoData[b]._aData,
                i = f.sDefaultContent,
                c = f.fnGetData(h, e, {
                    settings: a,
                    row: b,
                    col: c
                });
            if (c === l) return a.iDrawError != d && null === i && (R(a, 0, "Requested unknown parameter " + ("function" == typeof f.mData ? "{function}" : "'" + f.mData + "'") + " for row " + b, 4), a.iDrawError = d), i;
            if ((c === h || null === c) && null !== i) c = i;
            else if ("function" === typeof c) return c.call(h);
            return null === c && "display" == e ? "" : c
        }

        function Ha(a, b, c, e) {
            a.aoColumns[c].fnSetData(a.aoData[b]._aData, e, {
                settings: a,
                row: b,
                col: c
            })
        }

        function Ja(a) {
            return g.map(a.match(/(\\.|[^\.])+/g), function(a) {
                return a.replace(/\\./g, ".")
            })
        }

        function W(a) {
            if (g.isPlainObject(a)) {
                var b = {};
                g.each(a, function(a, c) {
                    c && (b[a] = W(c))
                });
                return function(a, c, f, h) {
                    var i = b[c] || b._;
                    return i !== l ? i(a, c, f, h) : a
                }
            }
            if (null === a) return function(a) {
                return a
            };
            if ("function" === typeof a) return function(b, c, f, h) {
                return a(b, c, f, h)
            };
            if ("string" ===
                typeof a && (-1 !== a.indexOf(".") || -1 !== a.indexOf("[") || -1 !== a.indexOf("("))) {
                var c = function(a, b, f) {
                    var h, i;
                    if ("" !== f) {
                        i = Ja(f);
                        for (var j = 0, g = i.length; j < g; j++) {
                            f = i[j].match(ba);
                            h = i[j].match(S);
                            if (f) {
                                i[j] = i[j].replace(ba, "");
                                "" !== i[j] && (a = a[i[j]]);
                                h = [];
                                i.splice(0, j + 1);
                                i = i.join(".");
                                j = 0;
                                for (g = a.length; j < g; j++) h.push(c(a[j], b, i));
                                a = f[0].substring(1, f[0].length - 1);
                                a = "" === a ? h : h.join(a);
                                break
                            } else if (h) {
                                i[j] = i[j].replace(S, "");
                                a = a[i[j]]();
                                continue
                            }
                            if (null === a || a[i[j]] === l) return l;
                            a = a[i[j]]
                        }
                    }
                    return a
                };
                return function(b,
                    d) {
                    return c(b, d, a)
                }
            }
            return function(b) {
                return b[a]
            }
        }

        function Q(a) {
            if (g.isPlainObject(a)) return Q(a._);
            if (null === a) return function() {};
            if ("function" === typeof a) return function(b, e, d) {
                a(b, "set", e, d)
            };
            if ("string" === typeof a && (-1 !== a.indexOf(".") || -1 !== a.indexOf("[") || -1 !== a.indexOf("("))) {
                var b = function(a, e, d) {
                    var d = Ja(d),
                        f;
                    f = d[d.length - 1];
                    for (var h, i, j = 0, g = d.length - 1; j < g; j++) {
                        h = d[j].match(ba);
                        i = d[j].match(S);
                        if (h) {
                            d[j] = d[j].replace(ba, "");
                            a[d[j]] = [];
                            f = d.slice();
                            f.splice(0, j + 1);
                            h = f.join(".");
                            i = 0;
                            for (g =
                                e.length; i < g; i++) f = {}, b(f, e[i], h), a[d[j]].push(f);
                            return
                        }
                        i && (d[j] = d[j].replace(S, ""), a = a[d[j]](e));
                        if (null === a[d[j]] || a[d[j]] === l) a[d[j]] = {};
                        a = a[d[j]]
                    }
                    if (f.match(S)) a[f.replace(S, "")](e);
                    else a[f.replace(ba, "")] = e
                };
                return function(c, e) {
                    return b(c, e, a)
                }
            }
            return function(b, e) {
                b[a] = e
            }
        }

        function Ka(a) {
            return C(a.aoData, "_aData")
        }

        function na(a) {
            a.aoData.length = 0;
            a.aiDisplayMaster.length = 0;
            a.aiDisplay.length = 0
        }

        function oa(a, b, c) {
            for (var e = -1, d = 0, f = a.length; d < f; d++) a[d] == b ? e = d : a[d] > b && a[d] --; - 1 != e && c === l &&
                a.splice(e, 1)
        }

        function ca(a, b, c, e) {
            var d = a.aoData[b],
                f, h = function(c, f) {
                    for (; c.childNodes.length;) c.removeChild(c.firstChild);
                    c.innerHTML = v(a, b, f, "display")
                };
            if ("dom" === c || (!c || "auto" === c) && "dom" === d.src) d._aData = ma(a, d, e, e === l ? l : d._aData).data;
            else {
                var i = d.anCells;
                if (i)
                    if (e !== l) h(i[e], e);
                    else {
                        c = 0;
                        for (f = i.length; c < f; c++) h(i[c], c)
                    }
            }
            d._aSortData = null;
            d._aFilterData = null;
            h = a.aoColumns;
            if (e !== l) h[e].sType = null;
            else {
                c = 0;
                for (f = h.length; c < f; c++) h[c].sType = null;
                La(d)
            }
        }

        function ma(a, b, c, e) {
            var d = [],
                f = b.firstChild,
                h, i = 0,
                j, n = a.aoColumns,
                m = a._rowReadObject,
                e = e || m ? {} : [],
                o = function(a, b) {
                    if ("string" === typeof a) {
                        var c = a.indexOf("@"); - 1 !== c && (c = a.substring(c + 1), Q(a)(e, b.getAttribute(c)))
                    }
                },
                a = function(a) {
                    if (c === l || c === i) h = n[i], j = g.trim(a.innerHTML), h && h._bAttrSrc ? (Q(h.mData._)(e, j), o(h.mData.sort, a), o(h.mData.type, a), o(h.mData.filter, a)) : m ? (h._setter || (h._setter = Q(h.mData)), h._setter(e, j)) : e[i] = j;
                    i++
                };
            if (f)
                for (; f;) {
                    b = f.nodeName.toUpperCase();
                    if ("TD" == b || "TH" == b) a(f), d.push(f);
                    f = f.nextSibling
                } else {
                    d = b.anCells;
                    f = 0;
                    for (b =
                        d.length; f < b; f++) a(d[f])
                }
            return {
                data: e,
                cells: d
            }
        }

        function Ia(a, b, c, e) {
            var d = a.aoData[b],
                f = d._aData,
                h = [],
                i, j, g, m, o;
            if (null === d.nTr) {
                i = c || P.createElement("tr");
                d.nTr = i;
                d.anCells = h;
                i._DT_RowIndex = b;
                La(d);
                m = 0;
                for (o = a.aoColumns.length; m < o; m++) {
                    g = a.aoColumns[m];
                    j = c ? e[m] : P.createElement(g.sCellType);
                    h.push(j);
                    if (!c || g.mRender || g.mData !== m) j.innerHTML = v(a, b, m, "display");
                    g.sClass && (j.className += " " + g.sClass);
                    g.bVisible && !c ? i.appendChild(j) : !g.bVisible && c && j.parentNode.removeChild(j);
                    g.fnCreatedCell && g.fnCreatedCell.call(a.oInstance,
                        j, v(a, b, m), f, b, m)
                }
                u(a, "aoRowCreatedCallback", null, [i, f, b])
            }
            d.nTr.setAttribute("role", "row")
        }

        function La(a) {
            var b = a.nTr,
                c = a._aData;
            if (b) {
                c.DT_RowId && (b.id = c.DT_RowId);
                if (c.DT_RowClass) {
                    var e = c.DT_RowClass.split(" ");
                    a.__rowc = a.__rowc ? Ma(a.__rowc.concat(e)) : e;
                    g(b).removeClass(a.__rowc.join(" ")).addClass(c.DT_RowClass)
                }
                c.DT_RowData && g(b).data(c.DT_RowData)
            }
        }

        function ib(a) {
            var b, c, e, d, f, h = a.nTHead,
                i = a.nTFoot,
                j = 0 === g("th, td", h).length,
                n = a.oClasses,
                m = a.aoColumns;
            j && (d = g("<tr/>").appendTo(h));
            b = 0;
            for (c =
                m.length; b < c; b++) f = m[b], e = g(f.nTh).addClass(f.sClass), j && e.appendTo(d), a.oFeatures.bSort && (e.addClass(f.sSortingClass), !1 !== f.bSortable && (e.attr("tabindex", a.iTabIndex).attr("aria-controls", a.sTableId), Na(a, f.nTh, b))), f.sTitle != e.html() && e.html(f.sTitle), Oa(a, "header")(a, e, f, n);
            j && da(a.aoHeader, h);
            g(h).find(">tr").attr("role", "row");
            g(h).find(">tr>th, >tr>td").addClass(n.sHeaderTH);
            g(i).find(">tr>th, >tr>td").addClass(n.sFooterTH);
            if (null !== i) {
                a = a.aoFooter[0];
                b = 0;
                for (c = a.length; b < c; b++) f = m[b], f.nTf =
                    a[b].cell, f.sClass && g(f.nTf).addClass(f.sClass)
            }
        }

        function ea(a, b, c) {
            var e, d, f, h = [],
                i = [],
                j = a.aoColumns.length,
                n;
            if (b) {
                c === l && (c = !1);
                e = 0;
                for (d = b.length; e < d; e++) {
                    h[e] = b[e].slice();
                    h[e].nTr = b[e].nTr;
                    for (f = j - 1; 0 <= f; f--) !a.aoColumns[f].bVisible && !c && h[e].splice(f, 1);
                    i.push([])
                }
                e = 0;
                for (d = h.length; e < d; e++) {
                    if (a = h[e].nTr)
                        for (; f = a.firstChild;) a.removeChild(f);
                    f = 0;
                    for (b = h[e].length; f < b; f++)
                        if (n = j = 1, i[e][f] === l) {
                            a.appendChild(h[e][f].cell);
                            for (i[e][f] = 1; h[e + j] !== l && h[e][f].cell == h[e + j][f].cell;) i[e + j][f] = 1, j++;
                            for (; h[e][f + n] !== l && h[e][f].cell == h[e][f + n].cell;) {
                                for (c = 0; c < j; c++) i[e + c][f + n] = 1;
                                n++
                            }
                            g(h[e][f].cell).attr("rowspan", j).attr("colspan", n)
                        }
                }
            }
        }

        function L(a) {
            var b = u(a, "aoPreDrawCallback", "preDraw", [a]);
            if (-1 !== g.inArray(!1, b)) B(a, !1);
            else {
                var b = [],
                    c = 0,
                    e = a.asStripeClasses,
                    d = e.length,
                    f = a.oLanguage,
                    h = a.iInitDisplayStart,
                    i = "ssp" == A(a),
                    j = a.aiDisplay;
                a.bDrawing = !0;
                h !== l && -1 !== h && (a._iDisplayStart = i ? h : h >= a.fnRecordsDisplay() ? 0 : h, a.iInitDisplayStart = -1);
                var h = a._iDisplayStart,
                    n = a.fnDisplayEnd();
                if (a.bDeferLoading) a.bDeferLoading = !1, a.iDraw++, B(a, !1);
                else if (i) {
                    if (!a.bDestroying && !jb(a)) return
                } else a.iDraw++;
                if (0 !== j.length) {
                    f = i ? a.aoData.length : n;
                    for (i = i ? 0 : h; i < f; i++) {
                        var m = j[i],
                            o = a.aoData[m];
                        null === o.nTr && Ia(a, m);
                        m = o.nTr;
                        if (0 !== d) {
                            var k = e[c % d];
                            o._sRowStripe != k && (g(m).removeClass(o._sRowStripe).addClass(k), o._sRowStripe = k)
                        }
                        u(a, "aoRowCallback", null, [m, o._aData, c, i]);
                        b.push(m);
                        c++
                    }
                } else c = f.sZeroRecords, 1 == a.iDraw && "ajax" == A(a) ? c = f.sLoadingRecords : f.sEmptyTable && 0 === a.fnRecordsTotal() && (c = f.sEmptyTable), b[0] = g("<tr/>", {
                    "class": d ?
                        e[0] : ""
                }).append(g("<td />", {
                    valign: "top",
                    colSpan: aa(a),
                    "class": a.oClasses.sRowEmpty
                }).html(c))[0];
                u(a, "aoHeaderCallback", "header", [g(a.nTHead).children("tr")[0], Ka(a), h, n, j]);
                u(a, "aoFooterCallback", "footer", [g(a.nTFoot).children("tr")[0], Ka(a), h, n, j]);
                e = g(a.nTBody);
                e.children().detach();
                e.append(g(b));
                u(a, "aoDrawCallback", "draw", [a]);
                a.bSorted = !1;
                a.bFiltered = !1;
                a.bDrawing = !1
            }
        }

        function M(a, b) {
            var c = a.oFeatures,
                e = c.bFilter;
            c.bSort && kb(a);
            e ? fa(a, a.oPreviousSearch) : a.aiDisplay = a.aiDisplayMaster.slice();
            !0 !== b && (a._iDisplayStart = 0);
            a._drawHold = b;
            L(a);
            a._drawHold = !1
        }

        function lb(a) {
            var b = a.oClasses,
                c = g(a.nTable),
                c = g("<div/>").insertBefore(c),
                e = a.oFeatures,
                d = g("<div/>", {
                    id: a.sTableId + "_wrapper",
                    "class": b.sWrapper + (a.nTFoot ? "" : " " + b.sNoFooter)
                });
            a.nHolding = c[0];
            a.nTableWrapper = d[0];
            a.nTableReinsertBefore = a.nTable.nextSibling;
            for (var f = a.sDom.split(""), h, i, j, n, m, o, k = 0; k < f.length; k++) {
                h = null;
                i = f[k];
                if ("<" == i) {
                    j = g("<div/>")[0];
                    n = f[k + 1];
                    if ("'" == n || '"' == n) {
                        m = "";
                        for (o = 2; f[k + o] != n;) m += f[k + o], o++;
                        "H" == m ? m = b.sJUIHeader :
                            "F" == m && (m = b.sJUIFooter); - 1 != m.indexOf(".") ? (n = m.split("."), j.id = n[0].substr(1, n[0].length - 1), j.className = n[1]) : "#" == m.charAt(0) ? j.id = m.substr(1, m.length - 1) : j.className = m;
                        k += o
                    }
                    d.append(j);
                    d = g(j)
                } else if (">" == i) d = d.parent();
                else if ("l" == i && e.bPaginate && e.bLengthChange) h = mb(a);
                else if ("f" == i && e.bFilter) h = nb(a);
                else if ("r" == i && e.bProcessing) h = ob(a);
                else if ("t" == i) h = pb(a);
                else if ("i" == i && e.bInfo) h = qb(a);
                else if ("p" == i && e.bPaginate) h = rb(a);
                else if (0 !== p.ext.feature.length) {
                    j = p.ext.feature;
                    o = 0;
                    for (n = j.length; o <
                        n; o++)
                        if (i == j[o].cFeature) {
                            h = j[o].fnInit(a);
                            break
                        }
                }
                h && (j = a.aanFeatures, j[i] || (j[i] = []), j[i].push(h), d.append(h))
            }
            c.replaceWith(d)
        }

        function da(a, b) {
            var c = g(b).children("tr"),
                e, d, f, h, i, j, n, m, o, k;
            a.splice(0, a.length);
            f = 0;
            for (j = c.length; f < j; f++) a.push([]);
            f = 0;
            for (j = c.length; f < j; f++) {
                e = c[f];
                for (d = e.firstChild; d;) {
                    if ("TD" == d.nodeName.toUpperCase() || "TH" == d.nodeName.toUpperCase()) {
                        m = 1 * d.getAttribute("colspan");
                        o = 1 * d.getAttribute("rowspan");
                        m = !m || 0 === m || 1 === m ? 1 : m;
                        o = !o || 0 === o || 1 === o ? 1 : o;
                        h = 0;
                        for (i = a[f]; i[h];) h++;
                        n = h;
                        k = 1 === m ? !0 : !1;
                        for (i = 0; i < m; i++)
                            for (h = 0; h < o; h++) a[f + h][n + i] = {
                                cell: d,
                                unique: k
                            }, a[f + h].nTr = e
                    }
                    d = d.nextSibling
                }
            }
        }

        function pa(a, b, c) {
            var e = [];
            c || (c = a.aoHeader, b && (c = [], da(c, b)));
            for (var b = 0, d = c.length; b < d; b++)
                for (var f = 0, h = c[b].length; f < h; f++)
                    if (c[b][f].unique && (!e[f] || !a.bSortCellsTop)) e[f] = c[b][f].cell;
            return e
        }

        function qa(a, b, c) {
            u(a, "aoServerParams", "serverParams", [b]);
            if (b && g.isArray(b)) {
                var e = {},
                    d = /(.*?)\[\]$/;
                g.each(b, function(a, b) {
                    var c = b.name.match(d);
                    c ? (c = c[0], e[c] || (e[c] = []), e[c].push(b.value)) :
                        e[b.name] = b.value
                });
                b = e
            }
            var f, h = a.ajax,
                i = a.oInstance;
            if (g.isPlainObject(h) && h.data) {
                f = h.data;
                var j = g.isFunction(f) ? f(b) : f,
                    b = g.isFunction(f) && j ? j : g.extend(!0, b, j);
                delete h.data
            }
            j = {
                data: b,
                success: function(b) {
                    var f = b.error || b.sError;
                    f && a.oApi._fnLog(a, 0, f);
                    a.json = b;
                    u(a, null, "xhr", [a, b]);
                    c(b)
                },
                dataType: "json",
                cache: !1,
                type: a.sServerMethod,
                error: function(b, c) {
                    var f = a.oApi._fnLog;
                    "parsererror" == c ? f(a, 0, "Invalid JSON response", 1) : 4 === b.readyState && f(a, 0, "Ajax error", 7);
                    B(a, !1)
                }
            };
            a.oAjaxData = b;
            u(a, null, "preXhr", [a, b]);
            a.fnServerData ? a.fnServerData.call(i, a.sAjaxSource, g.map(b, function(a, b) {
                return {
                    name: b,
                    value: a
                }
            }), c, a) : a.sAjaxSource || "string" === typeof h ? a.jqXHR = g.ajax(g.extend(j, {
                url: h || a.sAjaxSource
            })) : g.isFunction(h) ? a.jqXHR = h.call(i, b, c, a) : (a.jqXHR = g.ajax(g.extend(j, h)), h.data = f)
        }

        function jb(a) {
            return a.bAjaxDataGet ? (a.iDraw++, B(a, !0), qa(a, sb(a), function(b) {
                tb(a, b)
            }), !1) : !0
        }

        function sb(a) {
            var b = a.aoColumns,
                c = b.length,
                e = a.oFeatures,
                d = a.oPreviousSearch,
                f = a.aoPreSearchCols,
                h, i = [],
                j, n, m, o = T(a);
            h = a._iDisplayStart;
            j = !1 !== e.bPaginate ? a._iDisplayLength : -1;
            var k = function(a, b) {
                i.push({
                    name: a,
                    value: b
                })
            };
            k("sEcho", a.iDraw);
            k("iColumns", c);
            k("sColumns", C(b, "sName").join(","));
            k("iDisplayStart", h);
            k("iDisplayLength", j);
            var l = {
                draw: a.iDraw,
                columns: [],
                order: [],
                start: h,
                length: j,
                search: {
                    value: d.sSearch,
                    regex: d.bRegex
                }
            };
            for (h = 0; h < c; h++) n = b[h], m = f[h], j = "function" == typeof n.mData ? "function" : n.mData, l.columns.push({
                    data: j,
                    name: n.sName,
                    searchable: n.bSearchable,
                    orderable: n.bSortable,
                    search: {
                        value: m.sSearch,
                        regex: m.bRegex
                    }
                }),
                k("mDataProp_" + h, j), e.bFilter && (k("sSearch_" + h, m.sSearch), k("bRegex_" + h, m.bRegex), k("bSearchable_" + h, n.bSearchable)), e.bSort && k("bSortable_" + h, n.bSortable);
            e.bFilter && (k("sSearch", d.sSearch), k("bRegex", d.bRegex));
            e.bSort && (g.each(o, function(a, b) {
                l.order.push({
                    column: b.col,
                    dir: b.dir
                });
                k("iSortCol_" + a, b.col);
                k("sSortDir_" + a, b.dir)
            }), k("iSortingCols", o.length));
            b = p.ext.legacy.ajax;
            return null === b ? a.sAjaxSource ? i : l : b ? i : l
        }

        function tb(a, b) {
            var c = b.sEcho !== l ? b.sEcho : b.draw,
                e = b.iTotalRecords !== l ? b.iTotalRecords :
                b.recordsTotal,
                d = b.iTotalDisplayRecords !== l ? b.iTotalDisplayRecords : b.recordsFiltered;
            if (c) {
                if (1 * c < a.iDraw) return;
                a.iDraw = 1 * c
            }
            na(a);
            a._iRecordsTotal = parseInt(e, 10);
            a._iRecordsDisplay = parseInt(d, 10);
            c = ra(a, b);
            e = 0;
            for (d = c.length; e < d; e++) I(a, c[e]);
            a.aiDisplay = a.aiDisplayMaster.slice();
            a.bAjaxDataGet = !1;
            L(a);
            a._bInitComplete || sa(a, b);
            a.bAjaxDataGet = !0;
            B(a, !1)
        }

        function ra(a, b) {
            var c = g.isPlainObject(a.ajax) && a.ajax.dataSrc !== l ? a.ajax.dataSrc : a.sAjaxDataProp;
            return "data" === c ? b.aaData || b[c] : "" !== c ? W(c)(b) :
                b
        }

        function nb(a) {
            var b = a.oClasses,
                c = a.sTableId,
                e = a.oLanguage,
                d = a.oPreviousSearch,
                f = a.aanFeatures,
                h = '<input type="search" class="' + b.sFilterInput + '"/>',
                i = e.sSearch,
                i = i.match(/_INPUT_/) ? i.replace("_INPUT_", h) : i + h,
                b = g("<div/>", {
                    id: !f.f ? c + "_filter" : null,
                    "class": b.sFilter
                }).append(g("<label/>").append(i)),
                f = function() {
                    var b = !this.value ? "" : this.value;
                    b != d.sSearch && (fa(a, {
                        sSearch: b,
                        bRegex: d.bRegex,
                        bSmart: d.bSmart,
                        bCaseInsensitive: d.bCaseInsensitive
                    }), a._iDisplayStart = 0, L(a))
                },
                h = null !== a.searchDelay ? a.searchDelay :
                "ssp" === A(a) ? 400 : 0,
                j = g("input", b).val(d.sSearch).attr("placeholder", e.sSearchPlaceholder).bind("keyup.DT search.DT input.DT paste.DT cut.DT", h ? ta(f, h) : f).bind("keypress.DT", function(a) {
                    if (13 == a.keyCode) return !1
                }).attr("aria-controls", c);
            g(a.nTable).on("search.dt.DT", function(b, c) {
                if (a === c) try {
                    j[0] !== P.activeElement && j.val(d.sSearch)
                } catch (f) {}
            });
            return b[0]
        }

        function fa(a, b, c) {
            var e = a.oPreviousSearch,
                d = a.aoPreSearchCols,
                f = function(a) {
                    e.sSearch = a.sSearch;
                    e.bRegex = a.bRegex;
                    e.bSmart = a.bSmart;
                    e.bCaseInsensitive =
                        a.bCaseInsensitive
                };
            Ga(a);
            if ("ssp" != A(a)) {
                ub(a, b.sSearch, c, b.bEscapeRegex !== l ? !b.bEscapeRegex : b.bRegex, b.bSmart, b.bCaseInsensitive);
                f(b);
                for (b = 0; b < d.length; b++) vb(a, d[b].sSearch, b, d[b].bEscapeRegex !== l ? !d[b].bEscapeRegex : d[b].bRegex, d[b].bSmart, d[b].bCaseInsensitive);
                wb(a)
            } else f(b);
            a.bFiltered = !0;
            u(a, null, "search", [a])
        }

        function wb(a) {
            for (var b = p.ext.search, c = a.aiDisplay, e, d, f = 0, h = b.length; f < h; f++) {
                for (var i = [], j = 0, g = c.length; j < g; j++) d = c[j], e = a.aoData[d], b[f](a, e._aFilterData, d, e._aData, j) && i.push(d);
                c.length = 0;
                c.push.apply(c, i)
            }
        }

        function vb(a, b, c, e, d, f) {
            if ("" !== b)
                for (var h = a.aiDisplay, e = Pa(b, e, d, f), d = h.length - 1; 0 <= d; d--) b = a.aoData[h[d]]._aFilterData[c], e.test(b) || h.splice(d, 1)
        }

        function ub(a, b, c, e, d, f) {
            var e = Pa(b, e, d, f),
                d = a.oPreviousSearch.sSearch,
                f = a.aiDisplayMaster,
                h;
            0 !== p.ext.search.length && (c = !0);
            h = xb(a);
            if (0 >= b.length) a.aiDisplay = f.slice();
            else {
                if (h || c || d.length > b.length || 0 !== b.indexOf(d) || a.bSorted) a.aiDisplay = f.slice();
                b = a.aiDisplay;
                for (c = b.length - 1; 0 <= c; c--) e.test(a.aoData[b[c]]._sFilterRow) ||
                    b.splice(c, 1)
            }
        }

        function Pa(a, b, c, e) {
            a = b ? a : ua(a);
            c && (a = "^(?=.*?" + g.map(a.match(/"[^"]+"|[^ ]+/g) || "", function(a) {
                if ('"' === a.charAt(0)) var b = a.match(/^"(.*)"$/),
                    a = b ? b[1] : a;
                return a.replace('"', "")
            }).join(")(?=.*?") + ").*$");
            return RegExp(a, e ? "i" : "")
        }

        function ua(a) {
            return a.replace(Xb, "\\$1")
        }

        function xb(a) {
            var b = a.aoColumns,
                c, e, d, f, h, i, g, n, m = p.ext.type.search;
            c = !1;
            e = 0;
            for (f = a.aoData.length; e < f; e++)
                if (n = a.aoData[e], !n._aFilterData) {
                    i = [];
                    d = 0;
                    for (h = b.length; d < h; d++) c = b[d], c.bSearchable ? (g = v(a, e, d, "filter"),
                        m[c.sType] && (g = m[c.sType](g)), null === g && (g = ""), "string" !== typeof g && g.toString && (g = g.toString())) : g = "", g.indexOf && -1 !== g.indexOf("&") && (va.innerHTML = g, g = Yb ? va.textContent : va.innerText), g.replace && (g = g.replace(/[\r\n]/g, "")), i.push(g);
                    n._aFilterData = i;
                    n._sFilterRow = i.join("  ");
                    c = !0
                }
            return c
        }

        function yb(a) {
            return {
                search: a.sSearch,
                smart: a.bSmart,
                regex: a.bRegex,
                caseInsensitive: a.bCaseInsensitive
            }
        }

        function zb(a) {
            return {
                sSearch: a.search,
                bSmart: a.smart,
                bRegex: a.regex,
                bCaseInsensitive: a.caseInsensitive
            }
        }

        function qb(a) {
            var b = a.sTableId,
                c = a.aanFeatures.i,
                e = g("<div/>", {
                    "class": a.oClasses.sInfo,
                    id: !c ? b + "_info" : null
                });
            c || (a.aoDrawCallback.push({
                fn: Ab,
                sName: "information"
            }), e.attr("role", "status").attr("aria-live", "polite"), g(a.nTable).attr("aria-describedby", b + "_info"));
            return e[0]
        }

        function Ab(a) {
            var b = a.aanFeatures.i;
            if (0 !== b.length) {
                var c = a.oLanguage,
                    e = a._iDisplayStart + 1,
                    d = a.fnDisplayEnd(),
                    f = a.fnRecordsTotal(),
                    h = a.fnRecordsDisplay(),
                    i = h ? c.sInfo : c.sInfoEmpty;
                h !== f && (i += " " + c.sInfoFiltered);
                i += c.sInfoPostFix;
                i = Bb(a, i);
                c = c.fnInfoCallback;
                null !== c && (i = c.call(a.oInstance, a, e, d, f, h, i));
                g(b).html(i)
            }
        }

        function Bb(a, b) {
            var c = a.fnFormatNumber,
                e = a._iDisplayStart + 1,
                d = a._iDisplayLength,
                f = a.fnRecordsDisplay(),
                h = -1 === d;
            return b.replace(/_START_/g, c.call(a, e)).replace(/_END_/g, c.call(a, a.fnDisplayEnd())).replace(/_MAX_/g, c.call(a, a.fnRecordsTotal())).replace(/_TOTAL_/g, c.call(a, f)).replace(/_PAGE_/g, c.call(a, h ? 1 : Math.ceil(e / d))).replace(/_PAGES_/g, c.call(a, h ? 1 : Math.ceil(f / d)))
        }

        function ga(a) {
            var b, c, e = a.iInitDisplayStart,
                d = a.aoColumns,
                f;
            c = a.oFeatures;
            if (a.bInitialised) {
                lb(a);
                ib(a);
                ea(a, a.aoHeader);
                ea(a, a.aoFooter);
                B(a, !0);
                c.bAutoWidth && Fa(a);
                b = 0;
                for (c = d.length; b < c; b++) f = d[b], f.sWidth && (f.nTh.style.width = s(f.sWidth));
                M(a);
                d = A(a);
                "ssp" != d && ("ajax" == d ? qa(a, [], function(c) {
                    var f = ra(a, c);
                    for (b = 0; b < f.length; b++) I(a, f[b]);
                    a.iInitDisplayStart = e;
                    M(a);
                    B(a, !1);
                    sa(a, c)
                }, a) : (B(a, !1), sa(a)))
            } else setTimeout(function() {
                ga(a)
            }, 200)
        }

        function sa(a, b) {
            a._bInitComplete = !0;
            b && X(a);
            u(a, "aoInitComplete", "init", [a, b])
        }

        function Qa(a, b) {
            var c =
                parseInt(b, 10);
            a._iDisplayLength = c;
            Ra(a);
            u(a, null, "length", [a, c])
        }

        function mb(a) {
            for (var b = a.oClasses, c = a.sTableId, e = a.aLengthMenu, d = g.isArray(e[0]), f = d ? e[0] : e, e = d ? e[1] : e, d = g("<select/>", {
                    name: c + "_length",
                    "aria-controls": c,
                    "class": b.sLengthSelect
                }), h = 0, i = f.length; h < i; h++) d[0][h] = new Option(e[h], f[h]);
            var j = g("<div><label/></div>").addClass(b.sLength);
            a.aanFeatures.l || (j[0].id = c + "_length");
            j.children().append(a.oLanguage.sLengthMenu.replace("_MENU_", d[0].outerHTML));
            g("select", j).val(a._iDisplayLength).bind("change.DT",
                function() {
                    Qa(a, g(this).val());
                    L(a)
                });
            g(a.nTable).bind("length.dt.DT", function(b, c, f) {
                a === c && g("select", j).val(f)
            });
            return j[0]
        }

        function rb(a) {
            var b = a.sPaginationType,
                c = p.ext.pager[b],
                e = "function" === typeof c,
                d = function(a) {
                    L(a)
                },
                b = g("<div/>").addClass(a.oClasses.sPaging + b)[0],
                f = a.aanFeatures;
            e || c.fnInit(a, b, d);
            f.p || (b.id = a.sTableId + "_paginate", a.aoDrawCallback.push({
                fn: function(a) {
                    if (e) {
                        var b = a._iDisplayStart,
                            g = a._iDisplayLength,
                            n = a.fnRecordsDisplay(),
                            m = -1 === g,
                            b = m ? 0 : Math.ceil(b / g),
                            g = m ? 1 : Math.ceil(n /
                                g),
                            n = c(b, g),
                            o, m = 0;
                        for (o = f.p.length; m < o; m++) Oa(a, "pageButton")(a, f.p[m], m, n, b, g)
                    } else c.fnUpdate(a, d)
                },
                sName: "pagination"
            }));
            return b
        }

        function Sa(a, b, c) {
            var e = a._iDisplayStart,
                d = a._iDisplayLength,
                f = a.fnRecordsDisplay();
            0 === f || -1 === d ? e = 0 : "number" === typeof b ? (e = b * d, e > f && (e = 0)) : "first" == b ? e = 0 : "previous" == b ? (e = 0 <= d ? e - d : 0, 0 > e && (e = 0)) : "next" == b ? e + d < f && (e += d) : "last" == b ? e = Math.floor((f - 1) / d) * d : R(a, 0, "Unknown paging action: " + b, 5);
            b = a._iDisplayStart !== e;
            a._iDisplayStart = e;
            b && (u(a, null, "page", [a]), c && L(a));
            return b
        }

        function ob(a) {
            return g("<div/>", {
                id: !a.aanFeatures.r ? a.sTableId + "_processing" : null,
                "class": a.oClasses.sProcessing
            }).html(a.oLanguage.sProcessing).insertBefore(a.nTable)[0]
        }

        function B(a, b) {
            a.oFeatures.bProcessing && g(a.aanFeatures.r).css("display", b ? "block" : "none");
            u(a, null, "processing", [a, b])
        }

        function pb(a) {
            var b = g(a.nTable);
            b.attr("role", "grid");
            var c = a.oScroll;
            if ("" === c.sX && "" === c.sY) return a.nTable;
            var e = c.sX,
                d = c.sY,
                f = a.oClasses,
                h = b.children("caption"),
                i = h.length ? h[0]._captionSide : null,
                j = g(b[0].cloneNode(!1)),
                n = g(b[0].cloneNode(!1)),
                m = b.children("tfoot");
            c.sX && "100%" === b.attr("width") && b.removeAttr("width");
            m.length || (m = null);
            c = g("<div/>", {
                "class": f.sScrollWrapper
            }).append(g("<div/>", {
                "class": f.sScrollHead
            }).css({
                overflow: "hidden",
                position: "relative",
                border: 0,
                width: e ? !e ? null : s(e) : "100%"
            }).append(g("<div/>", {
                "class": f.sScrollHeadInner
            }).css({
                "box-sizing": "content-box",
                width: c.sXInner || "100%"
            }).append(j.removeAttr("id").css("margin-left", 0).append("top" === i ? h : null).append(b.children("thead"))))).append(g("<div/>", {
                "class": f.sScrollBody
            }).css({
                overflow: "auto",
                height: !d ? null : s(d),
                width: !e ? null : s(e)
            }).append(b));
            m && c.append(g("<div/>", {
                "class": f.sScrollFoot
            }).css({
                overflow: "hidden",
                border: 0,
                width: e ? !e ? null : s(e) : "100%"
            }).append(g("<div/>", {
                "class": f.sScrollFootInner
            }).append(n.removeAttr("id").css("margin-left", 0).append("bottom" === i ? h : null).append(b.children("tfoot")))));
            var b = c.children(),
                o = b[0],
                f = b[1],
                k = m ? b[2] : null;
            e && g(f).scroll(function() {
                var a = this.scrollLeft;
                o.scrollLeft = a;
                m && (k.scrollLeft = a)
            });
            a.nScrollHead =
                o;
            a.nScrollBody = f;
            a.nScrollFoot = k;
            a.aoDrawCallback.push({
                fn: Y,
                sName: "scrolling"
            });
            return c[0]
        }

        function Y(a) {
            var b = a.oScroll,
                c = b.sX,
                e = b.sXInner,
                d = b.sY,
                f = b.iBarWidth,
                h = g(a.nScrollHead),
                i = h[0].style,
                j = h.children("div"),
                n = j[0].style,
                m = j.children("table"),
                j = a.nScrollBody,
                o = g(j),
                k = j.style,
                l = g(a.nScrollFoot).children("div"),
                p = l.children("table"),
                r = g(a.nTHead),
                q = g(a.nTable),
                t = q[0],
                N = t.style,
                J = a.nTFoot ? g(a.nTFoot) : null,
                u = a.oBrowser,
                w = u.bScrollOversize,
                y, v, x, K, z, A = [],
                B = [],
                C = [],
                D, E = function(a) {
                    a = a.style;
                    a.paddingTop =
                        "0";
                    a.paddingBottom = "0";
                    a.borderTopWidth = "0";
                    a.borderBottomWidth = "0";
                    a.height = 0
                };
            q.children("thead, tfoot").remove();
            z = r.clone().prependTo(q);
            y = r.find("tr");
            x = z.find("tr");
            z.find("th, td").removeAttr("tabindex");
            J && (K = J.clone().prependTo(q), v = J.find("tr"), K = K.find("tr"));
            c || (k.width = "100%", h[0].style.width = "100%");
            g.each(pa(a, z), function(b, c) {
                D = ka(a, b);
                c.style.width = a.aoColumns[D].sWidth
            });
            J && F(function(a) {
                a.style.width = ""
            }, K);
            b.bCollapse && "" !== d && (k.height = o[0].offsetHeight + r[0].offsetHeight + "px");
            h = q.outerWidth();
            if ("" === c) {
                if (N.width = "100%", w && (q.find("tbody").height() > j.offsetHeight || "scroll" == o.css("overflow-y"))) N.width = s(q.outerWidth() - f)
            } else "" !== e ? N.width = s(e) : h == o.width() && o.height() < q.height() ? (N.width = s(h - f), q.outerWidth() > h - f && (N.width = s(h))) : N.width = s(h);
            h = q.outerWidth();
            F(E, x);
            F(function(a) {
                C.push(a.innerHTML);
                A.push(s(g(a).css("width")))
            }, x);
            F(function(a, b) {
                a.style.width = A[b]
            }, y);
            g(x).height(0);
            J && (F(E, K), F(function(a) {
                B.push(s(g(a).css("width")))
            }, K), F(function(a, b) {
                a.style.width =
                    B[b]
            }, v), g(K).height(0));
            F(function(a, b) {
                a.innerHTML = '<div class="ow_vote_dataTables_sizing" style="height:0;overflow:hidden;">' + C[b] + "</div>";
                a.style.width = A[b]
            }, x);
            J && F(function(a, b) {
                a.innerHTML = "";
                a.style.width = B[b]
            }, K);
            if (q.outerWidth() < h) {
                v = j.scrollHeight > j.offsetHeight || "scroll" == o.css("overflow-y") ? h + f : h;
                if (w && (j.scrollHeight > j.offsetHeight || "scroll" == o.css("overflow-y"))) N.width = s(v - f);
                ("" === c || "" !== e) && R(a, 1, "Possible column misalignment", 6)
            } else v = "100%";
            k.width = s(v);
            i.width = s(v);
            J && (a.nScrollFoot.style.width =
                s(v));
            !d && w && (k.height = s(t.offsetHeight + f));
            d && b.bCollapse && (k.height = s(d), b = c && t.offsetWidth > j.offsetWidth ? f : 0, t.offsetHeight < j.offsetHeight && (k.height = s(t.offsetHeight + b)));
            b = q.outerWidth();
            m[0].style.width = s(b);
            n.width = s(b);
            m = q.height() > j.clientHeight || "scroll" == o.css("overflow-y");
            u = "padding" + (u.bScrollbarLeft ? "Left" : "Right");
            n[u] = m ? f + "px" : "0px";
            J && (p[0].style.width = s(b), l[0].style.width = s(b), l[0].style[u] = m ? f + "px" : "0px");
            o.scroll();
            if ((a.bSorted || a.bFiltered) && !a._drawHold) j.scrollTop = 0
        }

        function F(a,
            b, c) {
            for (var e = 0, d = 0, f = b.length, h, g; d < f;) {
                h = b[d].firstChild;
                for (g = c ? c[d].firstChild : null; h;) 1 === h.nodeType && (c ? a(h, g, e) : a(h, e), e++), h = h.nextSibling, g = c ? g.nextSibling : null;
                d++
            }
        }

        function Fa(a) {
            var b = a.nTable,
                c = a.aoColumns,
                e = a.oScroll,
                d = e.sY,
                f = e.sX,
                h = e.sXInner,
                i = c.length,
                e = Z(a, "bVisible"),
                j = g("th", a.nTHead),
                n = b.getAttribute("width"),
                m = b.parentNode,
                o = !1,
                k, l;
            for (k = 0; k < e.length; k++) l = c[e[k]], null !== l.sWidth && (l.sWidth = Cb(l.sWidthOrig, m), o = !0);
            if (!o && !f && !d && i == aa(a) && i == j.length)
                for (k = 0; k < i; k++) c[k].sWidth =
                    s(j.eq(k).width());
            else {
                i = g(b).clone().empty().css("visibility", "hidden").removeAttr("id").append(g(a.nTHead).clone(!1)).append(g(a.nTFoot).clone(!1)).append(g("<tbody><tr/></tbody>"));
                i.find("tfoot th, tfoot td").css("width", "");
                var p = i.find("tbody tr"),
                    j = pa(a, i.find("thead")[0]);
                for (k = 0; k < e.length; k++) l = c[e[k]], j[k].style.width = null !== l.sWidthOrig && "" !== l.sWidthOrig ? s(l.sWidthOrig) : "";
                if (a.aoData.length)
                    for (k = 0; k < e.length; k++) o = e[k], l = c[o], g(Db(a, o)).clone(!1).append(l.sContentPadding).appendTo(p);
                i.appendTo(m);
                f && h ? i.width(h) : f ? (i.css("width", "auto"), i.width() < m.offsetWidth && i.width(m.offsetWidth)) : d ? i.width(m.offsetWidth) : n && i.width(n);
                Eb(a, i[0]);
                if (f) {
                    for (k = h = 0; k < e.length; k++) l = c[e[k]], d = g(j[k]).outerWidth(), h += null === l.sWidthOrig ? d : parseInt(l.sWidth, 10) + d - g(j[k]).width();
                    i.width(s(h));
                    b.style.width = s(h)
                }
                for (k = 0; k < e.length; k++)
                    if (l = c[e[k]], d = g(j[k]).width()) l.sWidth = s(d);
                b.style.width = s(i.css("width"));
                i.remove()
            }
            n && (b.style.width = s(n));
            if ((n || f) && !a._reszEvt) g(Da).bind("resize.DT-" + a.sInstance,
                ta(function() {
                    X(a)
                })), a._reszEvt = !0
        }

        function ta(a, b) {
            var c = b !== l ? b : 200,
                e, d;
            return function() {
                var b = this,
                    h = +new Date,
                    g = arguments;
                e && h < e + c ? (clearTimeout(d), d = setTimeout(function() {
                    e = l;
                    a.apply(b, g)
                }, c)) : e ? (e = h, a.apply(b, g)) : e = h
            }
        }

        function Cb(a, b) {
            if (!a) return 0;
            var c = g("<div/>").css("width", s(a)).appendTo(b || P.body),
                e = c[0].offsetWidth;
            c.remove();
            return e
        }

        function Eb(a, b) {
            var c = a.oScroll;
            if (c.sX || c.sY) c = !c.sX ? c.iBarWidth : 0, b.style.width = s(g(b).outerWidth() - c)
        }

        function Db(a, b) {
            var c = Fb(a, b);
            if (0 > c) return null;
            var e = a.aoData[c];
            return !e.nTr ? g("<td/>").html(v(a, c, b, "display"))[0] : e.anCells[b]
        }

        function Fb(a, b) {
            for (var c, e = -1, d = -1, f = 0, h = a.aoData.length; f < h; f++) c = v(a, f, b, "display") + "", c = c.replace(Zb, ""), c.length > e && (e = c.length, d = f);
            return d
        }

        function s(a) {
            return null === a ? "0px" : "number" == typeof a ? 0 > a ? "0px" : a + "px" : a.match(/\d$/) ? a + "px" : a
        }

        function Gb() {
            if (!p.__scrollbarWidth) {
                var a = g("<p/>").css({
                        width: "100%",
                        height: 200,
                        padding: 0
                    })[0],
                    b = g("<div/>").css({
                        position: "absolute",
                        top: 0,
                        left: 0,
                        width: 200,
                        height: 150,
                        padding: 0,
                        overflow: "hidden",
                        visibility: "hidden"
                    }).append(a).appendTo("body"),
                    c = a.offsetWidth;
                b.css("overflow", "scroll");
                a = a.offsetWidth;
                c === a && (a = b[0].clientWidth);
                b.remove();
                p.__scrollbarWidth = c - a
            }
            return p.__scrollbarWidth
        }

        function T(a) {
            var b, c, e = [],
                d = a.aoColumns,
                f, h, i, j;
            b = a.aaSortingFixed;
            c = g.isPlainObject(b);
            var n = [];
            f = function(a) {
                a.length && !g.isArray(a[0]) ? n.push(a) : n.push.apply(n, a)
            };
            g.isArray(b) && f(b);
            c && b.pre && f(b.pre);
            f(a.aaSorting);
            c && b.post && f(b.post);
            for (a = 0; a < n.length; a++) {
                j = n[a][0];
                f = d[j].aDataSort;
                b = 0;
                for (c = f.length; b < c; b++) h = f[b], i = d[h].sType || "string", n[a]._idx === l && (n[a]._idx = g.inArray(n[a][1], d[h].asSorting)), e.push({
                    src: j,
                    col: h,
                    dir: n[a][1],
                    index: n[a]._idx,
                    type: i,
                    formatter: p.ext.type.order[i + "-pre"]
                })
            }
            return e
        }

        function kb(a) {
            var b, c, e = [],
                d = p.ext.type.order,
                f = a.aoData,
                h = 0,
                g, j = a.aiDisplayMaster,
                n;
            Ga(a);
            n = T(a);
            b = 0;
            for (c = n.length; b < c; b++) g = n[b], g.formatter && h++, Hb(a, g.col);
            if ("ssp" != A(a) && 0 !== n.length) {
                b = 0;
                for (c = j.length; b < c; b++) e[j[b]] = b;
                h === n.length ? j.sort(function(a, b) {
                    var c, d, h, g, i = n.length,
                        j = f[a]._aSortData,
                        l = f[b]._aSortData;
                    for (h = 0; h < i; h++)
                        if (g = n[h], c = j[g.col], d = l[g.col], c = c < d ? -1 : c > d ? 1 : 0, 0 !== c) return "asc" === g.dir ? c : -c;
                    c = e[a];
                    d = e[b];
                    return c < d ? -1 : c > d ? 1 : 0
                }) : j.sort(function(a, b) {
                    var c, h, g, i, j = n.length,
                        l = f[a]._aSortData,
                        p = f[b]._aSortData;
                    for (g = 0; g < j; g++)
                        if (i = n[g], c = l[i.col], h = p[i.col], i = d[i.type + "-" + i.dir] || d["string-" + i.dir], c = i(c, h), 0 !== c) return c;
                    c = e[a];
                    h = e[b];
                    return c < h ? -1 : c > h ? 1 : 0
                })
            }
            a.bSorted = !0
        }

        function Ib(a) {
            for (var b, c, e = a.aoColumns, d = T(a), a = a.oLanguage.oAria, f = 0, h = e.length; f < h; f++) {
                c =
                    e[f];
                var g = c.asSorting;
                b = c.sTitle.replace(/<.*?>/g, "");
                var j = c.nTh;
                j.removeAttribute("aria-sort");
                c.bSortable && (0 < d.length && d[0].col == f ? (j.setAttribute("aria-sort", "asc" == d[0].dir ? "ascending" : "descending"), c = g[d[0].index + 1] || g[0]) : c = g[0], b += "asc" === c ? a.sSortAscending : a.sSortDescending);
                j.setAttribute("aria-label", b)
            }
        }

        function Ta(a, b, c, e) {
            var d = a.aaSorting,
                f = a.aoColumns[b].asSorting,
                h = function(a, b) {
                    var c = a._idx;
                    c === l && (c = g.inArray(a[1], f));
                    return c + 1 < f.length ? c + 1 : b ? null : 0
                };
            "number" === typeof d[0] &&
                (d = a.aaSorting = [d]);
            c && a.oFeatures.bSortMulti ? (c = g.inArray(b, C(d, "0")), -1 !== c ? (b = h(d[c], !0), null === b ? d.splice(c, 1) : (d[c][1] = f[b], d[c]._idx = b)) : (d.push([b, f[0], 0]), d[d.length - 1]._idx = 0)) : d.length && d[0][0] == b ? (b = h(d[0]), d.length = 1, d[0][1] = f[b], d[0]._idx = b) : (d.length = 0, d.push([b, f[0]]), d[0]._idx = 0);
            M(a);
            "function" == typeof e && e(a)
        }

        function Na(a, b, c, e) {
            var d = a.aoColumns[c];
            Ua(b, {}, function(b) {
                !1 !== d.bSortable && (a.oFeatures.bProcessing ? (B(a, !0), setTimeout(function() {
                    Ta(a, c, b.shiftKey, e);
                    "ssp" !== A(a) && B(a, !1)
                }, 0)) : Ta(a, c, b.shiftKey, e))
            })
        }

        function wa(a) {
            var b = a.aLastSort,
                c = a.oClasses.sSortColumn,
                e = T(a),
                d = a.oFeatures,
                f, h;
            if (d.bSort && d.bSortClasses) {
                d = 0;
                for (f = b.length; d < f; d++) h = b[d].src, g(C(a.aoData, "anCells", h)).removeClass(c + (2 > d ? d + 1 : 3));
                d = 0;
                for (f = e.length; d < f; d++) h = e[d].src, g(C(a.aoData, "anCells", h)).addClass(c + (2 > d ? d + 1 : 3))
            }
            a.aLastSort = e
        }

        function Hb(a, b) {
            var c = a.aoColumns[b],
                e = p.ext.order[c.sSortDataType],
                d;
            e && (d = e.call(a.oInstance, a, b, $(a, b)));
            for (var f, h = p.ext.type.order[c.sType + "-pre"], g = 0, j = a.aoData.length; g <
                j; g++)
                if (c = a.aoData[g], c._aSortData || (c._aSortData = []), !c._aSortData[b] || e) f = e ? d[g] : v(a, g, b, "sort"), c._aSortData[b] = h ? h(f) : f
        }

        function xa(a) {
            if (a.oFeatures.bStateSave && !a.bDestroying) {
                var b = {
                    time: +new Date,
                    start: a._iDisplayStart,
                    length: a._iDisplayLength,
                    order: g.extend(!0, [], a.aaSorting),
                    search: yb(a.oPreviousSearch),
                    columns: g.map(a.aoColumns, function(b, e) {
                        return {
                            visible: b.bVisible,
                            search: yb(a.aoPreSearchCols[e])
                        }
                    })
                };
                u(a, "aoStateSaveParams", "stateSaveParams", [a, b]);
                a.oSavedState = b;
                a.fnStateSaveCallback.call(a.oInstance,
                    a, b)
            }
        }

        function Jb(a) {
            var b, c, e = a.aoColumns;
            if (a.oFeatures.bStateSave) {
                var d = a.fnStateLoadCallback.call(a.oInstance, a);
                if (d && d.time && (b = u(a, "aoStateLoadParams", "stateLoadParams", [a, d]), -1 === g.inArray(!1, b) && (b = a.iStateDuration, !(0 < b && d.time < +new Date - 1E3 * b) && e.length === d.columns.length))) {
                    a.oLoadedState = g.extend(!0, {}, d);
                    a._iDisplayStart = d.start;
                    a.iInitDisplayStart = d.start;
                    a._iDisplayLength = d.length;
                    a.aaSorting = [];
                    g.each(d.order, function(b, c) {
                        a.aaSorting.push(c[0] >= e.length ? [0, c[1]] : c)
                    });
                    g.extend(a.oPreviousSearch,
                        zb(d.search));
                    b = 0;
                    for (c = d.columns.length; b < c; b++) {
                        var f = d.columns[b];
                        e[b].bVisible = f.visible;
                        g.extend(a.aoPreSearchCols[b], zb(f.search))
                    }
                    u(a, "aoStateLoaded", "stateLoaded", [a, d])
                }
            }
        }

        function ya(a) {
            var b = p.settings,
                a = g.inArray(a, C(b, "nTable"));
            return -1 !== a ? b[a] : null
        }

        function R(a, b, c, e) {
            c = "DataTables warning: " + (null !== a ? "table id=" + a.sTableId + " - " : "") + c;
            e && (c += ". For more information about this error, please contact ohiowebtech" + e);
            if (b) Da.console && console.log && console.log(c);
            else if (a = p.ext,
                "alert" == (a.sErrMode || a.errMode)) alert(c);
            else throw Error(c);
        }

        function D(a, b, c, e) {
            g.isArray(c) ? g.each(c, function(c, f) {
                g.isArray(f) ? D(a, b, f[0], f[1]) : D(a, b, f)
            }) : (e === l && (e = c), b[c] !== l && (a[e] = b[c]))
        }

        function Kb(a, b, c) {
            var e, d;
            for (d in b) b.hasOwnProperty(d) && (e = b[d], g.isPlainObject(e) ? (g.isPlainObject(a[d]) || (a[d] = {}), g.extend(!0, a[d], e)) : a[d] = c && "data" !== d && "aaData" !== d && g.isArray(e) ? e.slice() : e);
            return a
        }

        function Ua(a, b, c) {
            g(a).bind("click.DT", b, function(b) {
                a.blur();
                c(b)
            }).bind("keypress.DT", b, function(a) {
                13 ===
                    a.which && (a.preventDefault(), c(a))
            }).bind("selectstart.DT", function() {
                return !1
            })
        }

        function x(a, b, c, e) {
            c && a[b].push({
                fn: c,
                sName: e
            })
        }

        function u(a, b, c, e) {
            var d = [];
            b && (d = g.map(a[b].slice().reverse(), function(b) {
                return b.fn.apply(a.oInstance, e)
            }));
            null !== c && g(a.nTable).trigger(c + ".dt", e);
            return d
        }

        function Ra(a) {
            var b = a._iDisplayStart,
                c = a.fnDisplayEnd(),
                e = a._iDisplayLength;
            b >= c && (b = c - e);
            b -= b % e;
            if (-1 === e || 0 > b) b = 0;
            a._iDisplayStart = b
        }

        function Oa(a, b) {
            var c = a.renderer,
                e = p.ext.renderer[b];
            return g.isPlainObject(c) &&
                c[b] ? e[c[b]] || e._ : "string" === typeof c ? e[c] || e._ : e._
        }

        function A(a) {
            return a.oFeatures.bServerSide ? "ssp" : a.ajax || a.sAjaxSource ? "ajax" : "dom"
        }

        function Va(a, b) {
            var c = [],
                c = Lb.numbers_length,
                e = Math.floor(c / 2);
            b <= c ? c = U(0, b) : a <= e ? (c = U(0, c - 2), c.push("ellipsis"), c.push(b - 1)) : (a >= b - 1 - e ? c = U(b - (c - 2), b) : (c = U(a - 1, a + 2), c.push("ellipsis"), c.push(b - 1)), c.splice(0, 0, "ellipsis"), c.splice(0, 0, 0));
            c.DT_el = "span";
            return c
        }

        function cb(a) {
            g.each({
                num: function(b) {
                    return za(b, a)
                },
                "num-fmt": function(b) {
                    return za(b, a, Wa)
                },
                "html-num": function(b) {
                    return za(b,
                        a, Aa)
                },
                "html-num-fmt": function(b) {
                    return za(b, a, Aa, Wa)
                }
            }, function(b, c) {
                w.type.order[b + a + "-pre"] = c;
                b.match(/^html\-/) && (w.type.search[b + a] = w.type.search.html)
            })
        }

        function Mb(a) {
            return function() {
                var b = [ya(this[p.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));
                return p.ext.internal[a].apply(this, b)
            }
        }
        var p, w, q, r, t, Xa = {},
            Nb = /[\r\n]/g,
            Aa = /<.*?>/g,
            $b = /^[\w\+\-]/,
            ac = /[\w\+\-]$/,
            Xb = RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\|\\$|\\^|\\-)", "g"),
            Wa = /[',$\u00a3\u20ac\u00a5%\u2009\u202F]/g,
            H = function(a) {
                return !a || !0 === a || "-" === a ? !0 : !1
            },
            Ob = function(a) {
                var b = parseInt(a, 10);
                return !isNaN(b) && isFinite(a) ? b : null
            },
            Pb = function(a, b) {
                Xa[b] || (Xa[b] = RegExp(ua(b), "g"));
                return "string" === typeof a && "." !== b ? a.replace(/\./g, "").replace(Xa[b], ".") : a
            },
            Ya = function(a, b, c) {
                var e = "string" === typeof a;
                b && e && (a = Pb(a, b));
                c && e && (a = a.replace(Wa, ""));
                return H(a) || !isNaN(parseFloat(a)) && isFinite(a)
            },
            Qb = function(a, b, c) {
                return H(a) ? !0 : !(H(a) || "string" === typeof a) ? null : Ya(a.replace(Aa, ""), b, c) ? !0 : null
            },
            C = function(a,
                b, c) {
                var e = [],
                    d = 0,
                    f = a.length;
                if (c !== l)
                    for (; d < f; d++) a[d] && a[d][b] && e.push(a[d][b][c]);
                else
                    for (; d < f; d++) a[d] && e.push(a[d][b]);
                return e
            },
            ha = function(a, b, c, e) {
                var d = [],
                    f = 0,
                    h = b.length;
                if (e !== l)
                    for (; f < h; f++) a[b[f]][c] && d.push(a[b[f]][c][e]);
                else
                    for (; f < h; f++) d.push(a[b[f]][c]);
                return d
            },
            U = function(a, b) {
                var c = [],
                    e;
                b === l ? (b = 0, e = a) : (e = b, b = a);
                for (var d = b; d < e; d++) c.push(d);
                return c
            },
            Rb = function(a) {
                for (var b = [], c = 0, e = a.length; c < e; c++) a[c] && b.push(a[c]);
                return b
            },
            Ma = function(a) {
                var b = [],
                    c, e, d = a.length,
                    f, h = 0;
                e = 0;
                a: for (; e < d; e++) {
                    c = a[e];
                    for (f = 0; f < h; f++)
                        if (b[f] === c) continue a;
                    b.push(c);
                    h++
                }
                return b
            },
            z = function(a, b, c) {
                a[b] !== l && (a[c] = a[b])
            },
            ba = /\[.*?\]$/,
            S = /\(\)$/,
            va = g("<div>")[0],
            Yb = va.textContent !== l,
            Zb = /<.*?>/g;
        p = function(a) {
            this.$ = function(a, b) {
                return this.api(!0).$(a, b)
            };
            this._ = function(a, b) {
                return this.api(!0).rows(a, b).data()
            };
            this.api = function(a) {
                return a ? new q(ya(this[w.iApiIndex])) : new q(this)
            };
            this.fnAddData = function(a, b) {
                var c = this.api(!0),
                    e = g.isArray(a) && (g.isArray(a[0]) || g.isPlainObject(a[0])) ?
                    c.rows.add(a) : c.row.add(a);
                (b === l || b) && c.draw();
                return e.flatten().toArray()
            };
            this.fnAdjustColumnSizing = function(a) {
                var b = this.api(!0).columns.adjust(),
                    c = b.settings()[0],
                    e = c.oScroll;
                a === l || a ? b.draw(!1) : ("" !== e.sX || "" !== e.sY) && Y(c)
            };
            this.fnClearTable = function(a) {
                var b = this.api(!0).clear();
                (a === l || a) && b.draw()
            };
            this.fnClose = function(a) {
                this.api(!0).row(a).child.hide()
            };
            this.fnDeleteRow = function(a, b, c) {
                var e = this.api(!0),
                    a = e.rows(a),
                    d = a.settings()[0],
                    g = d.aoData[a[0][0]];
                a.remove();
                b && b.call(this, d, g);
                (c === l || c) && e.draw();
                return g
            };
            this.fnDestroy = function(a) {
                this.api(!0).destroy(a)
            };
            this.fnDraw = function(a) {
                this.api(!0).draw(!a)
            };
            this.fnFilter = function(a, b, c, e, d, g) {
                d = this.api(!0);
                null === b || b === l ? d.search(a, c, e, g) : d.column(b).search(a, c, e, g);
                d.draw()
            };
            this.fnGetData = function(a, b) {
                var c = this.api(!0);
                if (a !== l) {
                    var e = a.nodeName ? a.nodeName.toLowerCase() : "";
                    return b !== l || "td" == e || "th" == e ? c.cell(a, b).data() : c.row(a).data() || null
                }
                return c.data().toArray()
            };
            this.fnGetNodes = function(a) {
                var b = this.api(!0);
                return a !== l ? b.row(a).node() : b.rows().nodes().flatten().toArray()
            };
            this.fnGetPosition = function(a) {
                var b = this.api(!0),
                    c = a.nodeName.toUpperCase();
                return "TR" == c ? b.row(a).index() : "TD" == c || "TH" == c ? (a = b.cell(a).index(), [a.row, a.columnVisible, a.column]) : null
            };
            this.fnIsOpen = function(a) {
                return this.api(!0).row(a).child.isShown()
            };
            this.fnOpen = function(a, b, c) {
                return this.api(!0).row(a).child(b, c).show().child()[0]
            };
            this.fnPageChange = function(a, b) {
                var c = this.api(!0).page(a);
                (b === l || b) && c.draw(!1)
            };
            this.fnSetColumnVis =
                function(a, b, c) {
                    a = this.api(!0).column(a).visible(b);
                    (c === l || c) && a.columns.adjust().draw()
                };
            this.fnSettings = function() {
                return ya(this[w.iApiIndex])
            };
            this.fnSort = function(a) {
                this.api(!0).order(a).draw()
            };
            this.fnSortListener = function(a, b, c) {
                this.api(!0).order.listener(a, b, c)
            };
            this.fnUpdate = function(a, b, c, e, d) {
                var g = this.api(!0);
                c === l || null === c ? g.row(b).data(a) : g.cell(b, c).data(a);
                (d === l || d) && g.columns.adjust();
                (e === l || e) && g.draw();
                return 0
            };
            this.fnVersionCheck = w.fnVersionCheck;
            var b = this,
                c = a === l,
                e = this.length;
            c && (a = {});
            this.oApi = this.internal = w.internal;
            for (var d in p.ext.internal) d && (this[d] = Mb(d));
            this.each(function() {
                var d = {},
                    d = 1 < e ? Kb(d, a, !0) : a,
                    h = 0,
                    i, j = this.getAttribute("id"),
                    n = !1,
                    m = p.defaults;
                if ("table" != this.nodeName.toLowerCase()) R(null, 0, "Non-table node initialisation (" + this.nodeName + ")", 2);
                else {
                    db(m);
                    eb(m.column);
                    G(m, m, !0);
                    G(m.column, m.column, !0);
                    G(m, d);
                    var o = p.settings,
                        h = 0;
                    for (i = o.length; h < i; h++) {
                        if (o[h].nTable == this) {
                            i = d.bRetrieve !== l ? d.bRetrieve : m.bRetrieve;
                            if (c || i) return o[h].oInstance;
                            if (d.bDestroy !==
                                l ? d.bDestroy : m.bDestroy) {
                                o[h].oInstance.fnDestroy();
                                break
                            } else {
                                R(o[h], 0, "Cannot reinitialise DataTable", 3);
                                return
                            }
                        }
                        if (o[h].sTableId == this.id) {
                            o.splice(h, 1);
                            break
                        }
                    }
                    if (null === j || "" === j) this.id = j = "DataTables_Table_" + p.ext._unique++;
                    var k = g.extend(!0, {}, p.models.oSettings, {
                        nTable: this,
                        oApi: b.internal,
                        oInit: d,
                        sDestroyWidth: g(this)[0].style.width,
                        sInstance: j,
                        sTableId: j
                    });
                    o.push(k);
                    k.oInstance = 1 === b.length ? b : g(this).ow_vote_dataTable();
                    db(d);
                    d.oLanguage && O(d.oLanguage);
                    d.aLengthMenu && !d.iDisplayLength && (d.iDisplayLength =
                        g.isArray(d.aLengthMenu[0]) ? d.aLengthMenu[0][0] : d.aLengthMenu[0]);
                    d = Kb(g.extend(!0, {}, m), d);
                    D(k.oFeatures, d, "bPaginate bLengthChange bFilter bSort bSortMulti bInfo bProcessing bAutoWidth bSortClasses bServerSide bDeferRender".split(" "));
                    D(k, d, ["asStripeClasses", "ajax", "fnServerData", "fnFormatNumber", "sServerMethod", "aaSorting", "aaSortingFixed", "aLengthMenu", "sPaginationType", "sAjaxSource", "sAjaxDataProp", "iStateDuration", "sDom", "bSortCellsTop", "iTabIndex", "fnStateLoadCallback", "fnStateSaveCallback",
                        "renderer", "searchDelay", ["iCookieDuration", "iStateDuration"],
                        ["oSearch", "oPreviousSearch"],
                        ["aoSearchCols", "aoPreSearchCols"],
                        ["iDisplayLength", "_iDisplayLength"],
                        ["bJQueryUI", "bJUI"]
                    ]);
                    D(k.oScroll, d, [
                        ["sScrollX", "sX"],
                        ["sScrollXInner", "sXInner"],
                        ["sScrollY", "sY"],
                        ["bScrollCollapse", "bCollapse"]
                    ]);
                    D(k.oLanguage, d, "fnInfoCallback");
                    x(k, "aoDrawCallback", d.fnDrawCallback, "user");
                    x(k, "aoServerParams", d.fnServerParams, "user");
                    x(k, "aoStateSaveParams", d.fnStateSaveParams, "user");
                    x(k, "aoStateLoadParams",
                        d.fnStateLoadParams, "user");
                    x(k, "aoStateLoaded", d.fnStateLoaded, "user");
                    x(k, "aoRowCallback", d.fnRowCallback, "user");
                    x(k, "aoRowCreatedCallback", d.fnCreatedRow, "user");
                    x(k, "aoHeaderCallback", d.fnHeaderCallback, "user");
                    x(k, "aoFooterCallback", d.fnFooterCallback, "user");
                    x(k, "aoInitComplete", d.fnInitComplete, "user");
                    x(k, "aoPreDrawCallback", d.fnPreDrawCallback, "user");
                    j = k.oClasses;
                    d.bJQueryUI ? (g.extend(j, p.ext.oJUIClasses, d.oClasses), d.sDom === m.sDom && "lfrtip" === m.sDom && (k.sDom = '<"H"lfr>t<"F"ip>'), k.renderer) ?
                        g.isPlainObject(k.renderer) && !k.renderer.header && (k.renderer.header = "jqueryui") : k.renderer = "jqueryui" : g.extend(j, p.ext.classes, d.oClasses);
                    g(this).addClass(j.sTable);
                    if ("" !== k.oScroll.sX || "" !== k.oScroll.sY) k.oScroll.iBarWidth = Gb();
                    !0 === k.oScroll.sX && (k.oScroll.sX = "100%");
                    k.iInitDisplayStart === l && (k.iInitDisplayStart = d.iDisplayStart, k._iDisplayStart = d.iDisplayStart);
                    null !== d.iDeferLoading && (k.bDeferLoading = !0, h = g.isArray(d.iDeferLoading), k._iRecordsDisplay = h ? d.iDeferLoading[0] : d.iDeferLoading, k._iRecordsTotal =
                        h ? d.iDeferLoading[1] : d.iDeferLoading);
                    var r = k.oLanguage;
                    g.extend(!0, r, d.oLanguage);
                    "" !== r.sUrl && (g.ajax({
                        dataType: "json",
                        url: r.sUrl,
                        success: function(a) {
                            O(a);
                            G(m.oLanguage, a);
                            g.extend(true, r, a);
                            ga(k)
                        },
                        error: function() {
                            ga(k)
                        }
                    }), n = !0);
                    null === d.asStripeClasses && (k.asStripeClasses = [j.sStripeOdd, j.sStripeEven]);
                    var h = k.asStripeClasses,
                        q = g("tbody tr:eq(0)", this); - 1 !== g.inArray(!0, g.map(h, function(a) {
                        return q.hasClass(a)
                    })) && (g("tbody tr", this).removeClass(h.join(" ")), k.asDestroyStripes = h.slice());
                    var o = [],
                        s, h = this.getElementsByTagName("thead");
                    0 !== h.length && (da(k.aoHeader, h[0]), o = pa(k));
                    if (null === d.aoColumns) {
                        s = [];
                        h = 0;
                        for (i = o.length; h < i; h++) s.push(null)
                    } else s = d.aoColumns;
                    h = 0;
                    for (i = s.length; h < i; h++) Ea(k, o ? o[h] : null);
                    hb(k, d.aoColumnDefs, s, function(a, b) {
                        ja(k, a, b)
                    });
                    if (q.length) {
                        var t = function(a, b) {
                            return a.getAttribute("data-" + b) ? b : null
                        };
                        g.each(ma(k, q[0]).cells, function(a, b) {
                            var c = k.aoColumns[a];
                            if (c.mData === a) {
                                var e = t(b, "sort") || t(b, "order"),
                                    d = t(b, "filter") || t(b, "search");
                                if (e !== null || d !== null) {
                                    c.mData = {
                                        _: a + ".display",
                                        sort: e !== null ? a + ".@data-" + e : l,
                                        type: e !== null ? a + ".@data-" + e : l,
                                        filter: d !== null ? a + ".@data-" + d : l
                                    };
                                    ja(k, a)
                                }
                            }
                        })
                    }
                    var v = k.oFeatures;
                    d.bStateSave && (v.bStateSave = !0, Jb(k, d), x(k, "aoDrawCallback", xa, "state_save"));
                    if (d.aaSorting === l) {
                        o = k.aaSorting;
                        h = 0;
                        for (i = o.length; h < i; h++) o[h][1] = k.aoColumns[h].asSorting[0]
                    }
                    wa(k);
                    v.bSort && x(k, "aoDrawCallback", function() {
                        if (k.bSorted) {
                            var a = T(k),
                                b = {};
                            g.each(a, function(a, c) {
                                b[c.src] = c.dir
                            });
                            u(k, null, "order", [k, a, b]);
                            Ib(k)
                        }
                    });
                    x(k, "aoDrawCallback", function() {
                        (k.bSorted ||
                            A(k) === "ssp" || v.bDeferRender) && wa(k)
                    }, "sc");
                    fb(k);
                    h = g(this).children("caption").each(function() {
                        this._captionSide = g(this).css("caption-side")
                    });
                    i = g(this).children("thead");
                    0 === i.length && (i = g("<thead/>").appendTo(this));
                    k.nTHead = i[0];
                    i = g(this).children("tbody");
                    0 === i.length && (i = g("<tbody/>").appendTo(this));
                    k.nTBody = i[0];
                    i = g(this).children("tfoot");
                    if (0 === i.length && 0 < h.length && ("" !== k.oScroll.sX || "" !== k.oScroll.sY)) i = g("<tfoot/>").appendTo(this);
                    0 === i.length || 0 === i.children().length ? g(this).addClass(j.sNoFooter) :
                        0 < i.length && (k.nTFoot = i[0], da(k.aoFooter, k.nTFoot));
                    if (d.aaData)
                        for (h = 0; h < d.aaData.length; h++) I(k, d.aaData[h]);
                    else(k.bDeferLoading || "dom" == A(k)) && la(k, g(k.nTBody).children("tr"));
                    k.aiDisplay = k.aiDisplayMaster.slice();
                    k.bInitialised = !0;
                    !1 === n && ga(k)
                }
            });
            b = null;
            return this
        };
        var Sb = [],
            y = Array.prototype,
            bc = function(a) {
                var b, c, e = p.settings,
                    d = g.map(e, function(a) {
                        return a.nTable
                    });
                if (a) {
                    if (a.nTable && a.oApi) return [a];
                    if (a.nodeName && "table" === a.nodeName.toLowerCase()) return b = g.inArray(a, d), -1 !== b ? [e[b]] :
                        null;
                    if (a && "function" === typeof a.settings) return a.settings().toArray();
                    "string" === typeof a ? c = g(a) : a instanceof g && (c = a)
                } else return [];
                if (c) return c.map(function() {
                    b = g.inArray(this, d);
                    return -1 !== b ? e[b] : null
                }).toArray()
            };
        q = function(a, b) {
            if (!this instanceof q) throw "DT API must be constructed as a new object";
            var c = [],
                e = function(a) {
                    (a = bc(a)) && c.push.apply(c, a)
                };
            if (g.isArray(a))
                for (var d = 0, f = a.length; d < f; d++) e(a[d]);
            else e(a);
            this.context = Ma(c);
            b && this.push.apply(this, b.toArray ? b.toArray() : b);
            this.selector = {
                rows: null,
                cols: null,
                opts: null
            };
            q.extend(this, this, Sb)
        };
        p.Api = q;
        q.prototype = {
            concat: y.concat,
            context: [],
            each: function(a) {
                for (var b = 0, c = this.length; b < c; b++) a.call(this, this[b], b, this);
                return this
            },
            eq: function(a) {
                var b = this.context;
                return b.length > a ? new q(b[a], this[a]) : null
            },
            filter: function(a) {
                var b = [];
                if (y.filter) b = y.filter.call(this, a, this);
                else
                    for (var c = 0, e = this.length; c < e; c++) a.call(this, this[c], c, this) && b.push(this[c]);
                return new q(this.context, b)
            },
            flatten: function() {
                var a = [];
                return new q(this.context,
                    a.concat.apply(a, this.toArray()))
            },
            join: y.join,
            indexOf: y.indexOf || function(a, b) {
                for (var c = b || 0, e = this.length; c < e; c++)
                    if (this[c] === a) return c;
                return -1
            },
            iterator: function(a, b, c, e) {
                var d = [],
                    f, h, g, j, n, m = this.context,
                    o, k, p = this.selector;
                "string" === typeof a && (e = c, c = b, b = a, a = !1);
                h = 0;
                for (g = m.length; h < g; h++) {
                    var r = new q(m[h]);
                    if ("table" === b) f = c.call(r, m[h], h), f !== l && d.push(f);
                    else if ("columns" === b || "rows" === b) f = c.call(r, m[h], this[h], h), f !== l && d.push(f);
                    else if ("column" === b || "column-rows" === b || "row" === b || "cell" ===
                        b) {
                        k = this[h];
                        "column-rows" === b && (o = Ba(m[h], p.opts));
                        j = 0;
                        for (n = k.length; j < n; j++) f = k[j], f = "cell" === b ? c.call(r, m[h], f.row, f.column, h, j) : c.call(r, m[h], f, h, j, o), f !== l && d.push(f)
                    }
                }
                return d.length || e ? (a = new q(m, a ? d.concat.apply([], d) : d), b = a.selector, b.rows = p.rows, b.cols = p.cols, b.opts = p.opts, a) : this
            },
            lastIndexOf: y.lastIndexOf || function(a, b) {
                return this.indexOf.apply(this.toArray.reverse(), arguments)
            },
            length: 0,
            map: function(a) {
                var b = [];
                if (y.map) b = y.map.call(this, a, this);
                else
                    for (var c = 0, e = this.length; c < e; c++) b.push(a.call(this,
                        this[c], c));
                return new q(this.context, b)
            },
            pluck: function(a) {
                return this.map(function(b) {
                    return b[a]
                })
            },
            pop: y.pop,
            push: y.push,
            reduce: y.reduce || function(a, b) {
                return gb(this, a, b, 0, this.length, 1)
            },
            reduceRight: y.reduceRight || function(a, b) {
                return gb(this, a, b, this.length - 1, -1, -1)
            },
            reverse: y.reverse,
            selector: null,
            shift: y.shift,
            sort: y.sort,
            splice: y.splice,
            toArray: function() {
                return y.slice.call(this)
            },
            to$: function() {
                return g(this)
            },
            toJQuery: function() {
                return g(this)
            },
            unique: function() {
                return new q(this.context,
                    Ma(this))
            },
            unshift: y.unshift
        };
        q.extend = function(a, b, c) {
            if (b && (b instanceof q || b.__dt_wrapper)) {
                var e, d, f, h = function(a, b, c) {
                    return function() {
                        var e = b.apply(a, arguments);
                        q.extend(e, e, c.methodExt);
                        return e
                    }
                };
                e = 0;
                for (d = c.length; e < d; e++) f = c[e], b[f.name] = "function" === typeof f.val ? h(a, f.val, f) : g.isPlainObject(f.val) ? {} : f.val, b[f.name].__dt_wrapper = !0, q.extend(a, b[f.name], f.propExt)
            }
        };
        q.register = r = function(a, b) {
            if (g.isArray(a))
                for (var c = 0, e = a.length; c < e; c++) q.register(a[c], b);
            else
                for (var d = a.split("."),
                        f = Sb, h, i, c = 0, e = d.length; c < e; c++) {
                    h = (i = -1 !== d[c].indexOf("()")) ? d[c].replace("()", "") : d[c];
                    var j;
                    a: {
                        j = 0;
                        for (var n = f.length; j < n; j++)
                            if (f[j].name === h) {
                                j = f[j];
                                break a
                            }
                        j = null
                    }
                    j || (j = {
                        name: h,
                        val: {},
                        methodExt: [],
                        propExt: []
                    }, f.push(j));
                    c === e - 1 ? j.val = b : f = i ? j.methodExt : j.propExt
                }
        };
        q.registerPlural = t = function(a, b, c) {
            q.register(a, c);
            q.register(b, function() {
                var a = c.apply(this, arguments);
                return a === this ? this : a instanceof q ? a.length ? g.isArray(a[0]) ? new q(a.context, a[0]) : a[0] : l : a
            })
        };
        r("tables()", function(a) {
            var b;
            if (a) {
                b = q;
                var c = this.context;
                if ("number" === typeof a) a = [c[a]];
                else var e = g.map(c, function(a) {
                        return a.nTable
                    }),
                    a = g(e).filter(a).map(function() {
                        var a = g.inArray(this, e);
                        return c[a]
                    }).toArray();
                b = new b(a)
            } else b = this;
            return b
        });
        r("table()", function(a) {
            var a = this.tables(a),
                b = a.context;
            return b.length ? new q(b[0]) : a
        });
        t("tables().nodes()", "table().node()", function() {
            return this.iterator("table", function(a) {
                return a.nTable
            }, 1)
        });
        t("tables().body()", "table().body()", function() {
            return this.iterator("table",
                function(a) {
                    return a.nTBody
                }, 1)
        });
        t("tables().header()", "table().header()", function() {
            return this.iterator("table", function(a) {
                return a.nTHead
            }, 1)
        });
        t("tables().footer()", "table().footer()", function() {
            return this.iterator("table", function(a) {
                return a.nTFoot
            }, 1)
        });
        t("tables().containers()", "table().container()", function() {
            return this.iterator("table", function(a) {
                return a.nTableWrapper
            }, 1)
        });
        r("draw()", function(a) {
            return this.iterator("table", function(b) {
                M(b, !1 === a)
            })
        });
        r("page()", function(a) {
            return a ===
                l ? this.page.info().page : this.iterator("table", function(b) {
                    Sa(b, a)
                })
        });
        r("page.info()", function() {
            if (0 === this.context.length) return l;
            var a = this.context[0],
                b = a._iDisplayStart,
                c = a._iDisplayLength,
                e = a.fnRecordsDisplay(),
                d = -1 === c;
            return {
                page: d ? 0 : Math.floor(b / c),
                pages: d ? 1 : Math.ceil(e / c),
                start: b,
                end: a.fnDisplayEnd(),
                length: c,
                recordsTotal: a.fnRecordsTotal(),
                recordsDisplay: e
            }
        });
        r("page.len()", function(a) {
            return a === l ? 0 !== this.context.length ? this.context[0]._iDisplayLength : l : this.iterator("table", function(b) {
                Qa(b,
                    a)
            })
        });
        var Tb = function(a, b, c) {
            "ssp" == A(a) ? M(a, b) : (B(a, !0), qa(a, [], function(c) {
                na(a);
                for (var c = ra(a, c), e = 0, h = c.length; e < h; e++) I(a, c[e]);
                M(a, b);
                B(a, !1)
            }));
            if (c) {
                var e = new q(a);
                e.one("draw", function() {
                    c(e.ajax.json())
                })
            }
        };
        r("ajax.json()", function() {
            var a = this.context;
            if (0 < a.length) return a[0].json
        });
        r("ajax.params()", function() {
            var a = this.context;
            if (0 < a.length) return a[0].oAjaxData
        });
        r("ajax.reload()", function(a, b) {
            return this.iterator("table", function(c) {
                Tb(c, !1 === b, a)
            })
        });
        r("ajax.url()", function(a) {
            var b =
                this.context;
            if (a === l) {
                if (0 === b.length) return l;
                b = b[0];
                return b.ajax ? g.isPlainObject(b.ajax) ? b.ajax.url : b.ajax : b.sAjaxSource
            }
            return this.iterator("table", function(b) {
                g.isPlainObject(b.ajax) ? b.ajax.url = a : b.ajax = a
            })
        });
        r("ajax.url().load()", function(a, b) {
            return this.iterator("table", function(c) {
                Tb(c, !1 === b, a)
            })
        });
        var Za = function(a, b) {
                var c = [],
                    e, d, f, h, i, j;
                e = typeof a;
                if (!a || "string" === e || "function" === e || a.length === l) a = [a];
                f = 0;
                for (h = a.length; f < h; f++) {
                    d = a[f] && a[f].split ? a[f].split(",") : [a[f]];
                    i = 0;
                    for (j =
                        d.length; i < j; i++)(e = b("string" === typeof d[i] ? g.trim(d[i]) : d[i])) && e.length && c.push.apply(c, e)
                }
                return c
            },
            $a = function(a) {
                a || (a = {});
                a.filter && !a.search && (a.search = a.filter);
                return {
                    search: a.search || "none",
                    order: a.order || "current",
                    page: a.page || "all"
                }
            },
            ab = function(a) {
                for (var b = 0, c = a.length; b < c; b++)
                    if (0 < a[b].length) return a[0] = a[b], a.length = 1, a.context = [a.context[b]], a;
                a.length = 0;
                return a
            },
            Ba = function(a, b) {
                var c, e, d, f = [],
                    h = a.aiDisplay;
                c = a.aiDisplayMaster;
                var i = b.search;
                e = b.order;
                d = b.page;
                if ("ssp" == A(a)) return "removed" ===
                    i ? [] : U(0, c.length);
                if ("current" == d) {
                    c = a._iDisplayStart;
                    for (e = a.fnDisplayEnd(); c < e; c++) f.push(h[c])
                } else if ("current" == e || "applied" == e) f = "none" == i ? c.slice() : "applied" == i ? h.slice() : g.map(c, function(a) {
                    return -1 === g.inArray(a, h) ? a : null
                });
                else if ("index" == e || "original" == e) {
                    c = 0;
                    for (e = a.aoData.length; c < e; c++) "none" == i ? f.push(c) : (d = g.inArray(c, h), (-1 === d && "removed" == i || 0 <= d && "applied" == i) && f.push(c))
                }
                return f
            };
        r("rows()", function(a, b) {
            a === l ? a = "" : g.isPlainObject(a) && (b = a, a = "");
            var b = $a(b),
                c = this.iterator("table",
                    function(c) {
                        var d = b;
                        return Za(a, function(a) {
                            var b = Ob(a);
                            if (b !== null && !d) return [b];
                            var i = Ba(c, d);
                            if (b !== null && g.inArray(b, i) !== -1) return [b];
                            if (!a) return i;
                            if (typeof a === "function") return g.map(i, function(b) {
                                var d = c.aoData[b];
                                return a(b, d._aData, d.nTr) ? b : null
                            });
                            b = Rb(ha(c.aoData, i, "nTr"));
                            return a.nodeName && g.inArray(a, b) !== -1 ? [a._DT_RowIndex] : g(b).filter(a).map(function() {
                                return this._DT_RowIndex
                            }).toArray()
                        })
                    }, 1);
            c.selector.rows = a;
            c.selector.opts = b;
            return c
        });
        r("rows().nodes()", function() {
            return this.iterator("row",
                function(a, b) {
                    return a.aoData[b].nTr || l
                }, 1)
        });
        r("rows().data()", function() {
            return this.iterator(!0, "rows", function(a, b) {
                return ha(a.aoData, b, "_aData")
            }, 1)
        });
        t("rows().cache()", "row().cache()", function(a) {
            return this.iterator("row", function(b, c) {
                var e = b.aoData[c];
                return "search" === a ? e._aFilterData : e._aSortData
            }, 1)
        });
        t("rows().invalidate()", "row().invalidate()", function(a) {
            return this.iterator("row", function(b, c) {
                ca(b, c, a)
            })
        });
        t("rows().indexes()", "row().index()", function() {
            return this.iterator("row",
                function(a, b) {
                    return b
                }, 1)
        });
        t("rows().remove()", "row().remove()", function() {
            var a = this;
            return this.iterator("row", function(b, c, e) {
                var d = b.aoData;
                d.splice(c, 1);
                for (var f = 0, h = d.length; f < h; f++) null !== d[f].nTr && (d[f].nTr._DT_RowIndex = f);
                g.inArray(c, b.aiDisplay);
                oa(b.aiDisplayMaster, c);
                oa(b.aiDisplay, c);
                oa(a[e], c, !1);
                Ra(b)
            })
        });
        r("rows.add()", function(a) {
            var b = this.iterator("table", function(b) {
                    var c, f, h, g = [];
                    f = 0;
                    for (h = a.length; f < h; f++) c = a[f], c.nodeName && "TR" === c.nodeName.toUpperCase() ? g.push(la(b, c)[0]) :
                        g.push(I(b, c));
                    return g
                }, 1),
                c = this.rows(-1);
            c.pop();
            c.push.apply(c, b.toArray());
            return c
        });
        r("row()", function(a, b) {
            return ab(this.rows(a, b))
        });
        r("row().data()", function(a) {
            var b = this.context;
            if (a === l) return b.length && this.length ? b[0].aoData[this[0]]._aData : l;
            b[0].aoData[this[0]]._aData = a;
            ca(b[0], this[0], "data");
            return this
        });
        r("row().node()", function() {
            var a = this.context;
            return a.length && this.length ? a[0].aoData[this[0]].nTr || null : null
        });
        r("row.add()", function(a) {
            a instanceof g && a.length && (a = a[0]);
            var b = this.iterator("table", function(b) {
                return a.nodeName && "TR" === a.nodeName.toUpperCase() ? la(b, a)[0] : I(b, a)
            });
            return this.row(b[0])
        });
        var bb = function(a, b) {
                var c = a.context;
                c.length && (c = c[0].aoData[b !== l ? b : a[0]], c._details && (c._details.remove(), c._detailsShow = l, c._details = l))
            },
            Ub = function(a, b) {
                var c = a.context;
                if (c.length && a.length) {
                    var e = c[0].aoData[a[0]];
                    if (e._details) {
                        (e._detailsShow = b) ? e._details.insertAfter(e.nTr): e._details.detach();
                        var d = c[0],
                            f = new q(d),
                            h = d.aoData;
                        f.off("draw.dt.DT_details column-visibility.dt.DT_details destroy.dt.DT_details");
                        0 < C(h, "_details").length && (f.on("draw.dt.DT_details", function(a, b) {
                            d === b && f.rows({
                                page: "current"
                            }).eq(0).each(function(a) {
                                a = h[a];
                                a._detailsShow && a._details.insertAfter(a.nTr)
                            })
                        }), f.on("column-visibility.dt.DT_details", function(a, b) {
                            if (d === b)
                                for (var c, e = aa(b), f = 0, g = h.length; f < g; f++) c = h[f], c._details && c._details.children("td[colspan]").attr("colspan", e)
                        }), f.on("destroy.dt.DT_details", function(a, b) {
                            if (d === b)
                                for (var c = 0, e = h.length; c < e; c++) h[c]._details && bb(f, c)
                        }))
                    }
                }
            };
        r("row().child()", function(a, b) {
            var c =
                this.context;
            if (a === l) return c.length && this.length ? c[0].aoData[this[0]]._details : l;
            if (!0 === a) this.child.show();
            else if (!1 === a) bb(this);
            else if (c.length && this.length) {
                var e = c[0],
                    c = c[0].aoData[this[0]],
                    d = [],
                    f = function(a, b) {
                        if (a.nodeName && "tr" === a.nodeName.toLowerCase()) d.push(a);
                        else {
                            var c = g("<tr><td/></tr>").addClass(b);
                            g("td", c).addClass(b).html(a)[0].colSpan = aa(e);
                            d.push(c[0])
                        }
                    };
                if (g.isArray(a) || a instanceof g)
                    for (var h = 0, i = a.length; h < i; h++) f(a[h], b);
                else f(a, b);
                c._details && c._details.remove();
                c._details =
                    g(d);
                c._detailsShow && c._details.insertAfter(c.nTr)
            }
            return this
        });
        r(["row().child.show()", "row().child().show()"], function() {
            Ub(this, !0);
            return this
        });
        r(["row().child.hide()", "row().child().hide()"], function() {
            Ub(this, !1);
            return this
        });
        r(["row().child.remove()", "row().child().remove()"], function() {
            bb(this);
            return this
        });
        r("row().child.isShown()", function() {
            var a = this.context;
            return a.length && this.length ? a[0].aoData[this[0]]._detailsShow || !1 : !1
        });
        var cc = /^(.+):(name|visIdx|visible)$/,
            Vb = function(a,
                b, c, e, d) {
                for (var c = [], e = 0, f = d.length; e < f; e++) c.push(v(a, d[e], b));
                return c
            };
        r("columns()", function(a, b) {
            a === l ? a = "" : g.isPlainObject(a) && (b = a, a = "");
            var b = $a(b),
                c = this.iterator("table", function(c) {
                    var d = a,
                        f = b,
                        h = c.aoColumns,
                        i = C(h, "sName"),
                        j = C(h, "nTh");
                    return Za(d, function(a) {
                        var b = Ob(a);
                        if (a === "") return U(h.length);
                        if (b !== null) return [b >= 0 ? b : h.length + b];
                        if (typeof a === "function") {
                            var d = Ba(c, f);
                            return g.map(h, function(b, f) {
                                return a(f, Vb(c, f, 0, 0, d), j[f]) ? f : null
                            })
                        }
                        var k = typeof a === "string" ? a.match(cc) : "";
                        if (k) switch (k[2]) {
                            case "visIdx":
                            case "visible":
                                b =
                                    parseInt(k[1], 10);
                                if (b < 0) {
                                    var l = g.map(h, function(a, b) {
                                        return a.bVisible ? b : null
                                    });
                                    return [l[l.length + b]]
                                }
                                return [ka(c, b)];
                            case "name":
                                return g.map(i, function(a, b) {
                                    return a === k[1] ? b : null
                                })
                        } else return g(j).filter(a).map(function() {
                            return g.inArray(this, j)
                        }).toArray()
                    })
                }, 1);
            c.selector.cols = a;
            c.selector.opts = b;
            return c
        });
        t("columns().header()", "column().header()", function() {
            return this.iterator("column", function(a, b) {
                return a.aoColumns[b].nTh
            }, 1)
        });
        t("columns().footer()", "column().footer()", function() {
            return this.iterator("column",
                function(a, b) {
                    return a.aoColumns[b].nTf
                }, 1)
        });
        t("columns().data()", "column().data()", function() {
            return this.iterator("column-rows", Vb, 1)
        });
        t("columns().dataSrc()", "column().dataSrc()", function() {
            return this.iterator("column", function(a, b) {
                return a.aoColumns[b].mData
            }, 1)
        });
        t("columns().cache()", "column().cache()", function(a) {
            return this.iterator("column-rows", function(b, c, e, d, f) {
                return ha(b.aoData, f, "search" === a ? "_aFilterData" : "_aSortData", c)
            }, 1)
        });
        t("columns().nodes()", "column().nodes()", function() {
            return this.iterator("column-rows",
                function(a, b, c, e, d) {
                    return ha(a.aoData, d, "anCells", b)
                }, 1)
        });
        t("columns().visible()", "column().visible()", function(a, b) {
            return this.iterator("column", function(c, e) {
                if (a === l) return c.aoColumns[e].bVisible;
                var d = c.aoColumns,
                    f = d[e],
                    h = c.aoData,
                    i, j, n;
                if (a !== l && f.bVisible !== a) {
                    if (a) {
                        var m = g.inArray(!0, C(d, "bVisible"), e + 1);
                        i = 0;
                        for (j = h.length; i < j; i++) n = h[i].nTr, d = h[i].anCells, n && n.insertBefore(d[e], d[m] || null)
                    } else g(C(c.aoData, "anCells", e)).detach();
                    f.bVisible = a;
                    ea(c, c.aoHeader);
                    ea(c, c.aoFooter);
                    if (b === l ||
                        b) X(c), (c.oScroll.sX || c.oScroll.sY) && Y(c);
                    u(c, null, "column-visibility", [c, e, a]);
                    xa(c)
                }
            })
        });
        t("columns().indexes()", "column().index()", function(a) {
            return this.iterator("column", function(b, c) {
                return "visible" === a ? $(b, c) : c
            }, 1)
        });
        r("columns.adjust()", function() {
            return this.iterator("table", function(a) {
                X(a)
            }, 1)
        });
        r("column.index()", function(a, b) {
            if (0 !== this.context.length) {
                var c = this.context[0];
                if ("fromVisible" === a || "toData" === a) return ka(c, b);
                if ("fromData" === a || "toVisible" === a) return $(c, b)
            }
        });
        r("column()",
            function(a, b) {
                return ab(this.columns(a, b))
            });
        r("cells()", function(a, b, c) {
            g.isPlainObject(a) && (typeof a.row !== l ? (c = b, b = null) : (c = a, a = null));
            g.isPlainObject(b) && (c = b, b = null);
            if (null === b || b === l) return this.iterator("table", function(b) {
                var e = a,
                    d = $a(c),
                    f = b.aoData,
                    h = Ba(b, d),
                    d = Rb(ha(f, h, "anCells")),
                    i = g([].concat.apply([], d)),
                    j, m = b.aoColumns.length,
                    n, p, r, q, s, t;
                return Za(e, function(a) {
                    var c = typeof a === "function";
                    if (a === null || a === l || c) {
                        n = [];
                        p = 0;
                        for (r = h.length; p < r; p++) {
                            j = h[p];
                            for (q = 0; q < m; q++) {
                                s = {
                                    row: j,
                                    column: q
                                };
                                if (c) {
                                    t = b.aoData[j];
                                    a(s, v(b, j, q), t.anCells[q]) && n.push(s)
                                } else n.push(s)
                            }
                        }
                        return n
                    }
                    return g.isPlainObject(a) ? [a] : i.filter(a).map(function(a, b) {
                        j = b.parentNode._DT_RowIndex;
                        return {
                            row: j,
                            column: g.inArray(b, f[j].anCells)
                        }
                    }).toArray()
                })
            });
            var e = this.columns(b, c),
                d = this.rows(a, c),
                f, h, i, j, n, m = this.iterator("table", function(a, b) {
                    f = [];
                    h = 0;
                    for (i = d[b].length; h < i; h++) {
                        j = 0;
                        for (n = e[b].length; j < n; j++) f.push({
                            row: d[b][h],
                            column: e[b][j]
                        })
                    }
                    return f
                }, 1);
            g.extend(m.selector, {
                cols: b,
                rows: a,
                opts: c
            });
            return m
        });
        t("cells().nodes()",
            "cell().node()",
            function() {
                return this.iterator("cell", function(a, b, c) {
                    return (a = a.aoData[b].anCells) ? a[c] : l
                }, 1)
            });
        r("cells().data()", function() {
            return this.iterator("cell", function(a, b, c) {
                return v(a, b, c)
            }, 1)
        });
        t("cells().cache()", "cell().cache()", function(a) {
            a = "search" === a ? "_aFilterData" : "_aSortData";
            return this.iterator("cell", function(b, c, e) {
                return b.aoData[c][a][e]
            }, 1)
        });
        t("cells().render()", "cell().render()", function(a) {
            return this.iterator("cell", function(b, c, e) {
                return v(b, c, e, a)
            }, 1)
        });
        t("cells().indexes()",
            "cell().index()",
            function() {
                return this.iterator("cell", function(a, b, c) {
                    return {
                        row: b,
                        column: c,
                        columnVisible: $(a, c)
                    }
                }, 1)
            });
        t("cells().invalidate()", "cell().invalidate()", function(a) {
            return this.iterator("cell", function(b, c, e) {
                ca(b, c, a, e)
            })
        });
        r("cell()", function(a, b, c) {
            return ab(this.cells(a, b, c))
        });
        r("cell().data()", function(a) {
            var b = this.context,
                c = this[0];
            if (a === l) return b.length && c.length ? v(b[0], c[0].row, c[0].column) : l;
            Ha(b[0], c[0].row, c[0].column, a);
            ca(b[0], c[0].row, "data", c[0].column);
            return this
        });
        r("order()", function(a, b) {
            var c = this.context;
            if (a === l) return 0 !== c.length ? c[0].aaSorting : l;
            "number" === typeof a ? a = [
                [a, b]
            ] : g.isArray(a[0]) || (a = Array.prototype.slice.call(arguments));
            return this.iterator("table", function(b) {
                b.aaSorting = a.slice()
            })
        });
        r("order.listener()", function(a, b, c) {
            return this.iterator("table", function(e) {
                Na(e, a, b, c)
            })
        });
        r(["columns().order()", "column().order()"], function(a) {
            var b = this;
            return this.iterator("table", function(c, e) {
                var d = [];
                g.each(b[e], function(b, c) {
                    d.push([c, a])
                });
                c.aaSorting = d
            })
        });
        r("search()", function(a, b, c, e) {
            var d = this.context;
            return a === l ? 0 !== d.length ? d[0].oPreviousSearch.sSearch : l : this.iterator("table", function(d) {
                d.oFeatures.bFilter && fa(d, g.extend({}, d.oPreviousSearch, {
                    sSearch: a + "",
                    bRegex: null === b ? !1 : b,
                    bSmart: null === c ? !0 : c,
                    bCaseInsensitive: null === e ? !0 : e
                }), 1)
            })
        });
        t("columns().search()", "column().search()", function(a, b, c, e) {
            return this.iterator("column", function(d, f) {
                var h = d.aoPreSearchCols;
                if (a === l) return h[f].sSearch;
                d.oFeatures.bFilter && (g.extend(h[f], {
                    sSearch: a + "",
                    bRegex: null === b ? !1 : b,
                    bSmart: null === c ? !0 : c,
                    bCaseInsensitive: null === e ? !0 : e
                }), fa(d, d.oPreviousSearch, 1))
            })
        });
        r("state()", function() {
            return this.context.length ? this.context[0].oSavedState : null
        });
        r("state.clear()", function() {
            return this.iterator("table", function(a) {
                a.fnStateSaveCallback.call(a.oInstance, a, {})
            })
        });
        r("state.loaded()", function() {
            return this.context.length ? this.context[0].oLoadedState : null
        });
        r("state.save()", function() {
            return this.iterator("table", function(a) {
                xa(a)
            })
        });
        p.versionCheck =
            p.fnVersionCheck = function(a) {
                for (var b = p.version.split("."), a = a.split("."), c, e, d = 0, f = a.length; d < f; d++)
                    if (c = parseInt(b[d], 10) || 0, e = parseInt(a[d], 10) || 0, c !== e) return c > e;
                return !0
            };
        p.isDataTable = p.fnIsDataTable = function(a) {
            var b = g(a).get(0),
                c = !1;
            g.each(p.settings, function(a, d) {
                if (d.nTable === b || d.nScrollHead === b || d.nScrollFoot === b) c = !0
            });
            return c
        };
        p.tables = p.fnTables = function(a) {
            return g.map(p.settings, function(b) {
                if (!a || a && g(b.nTable).is(":visible")) return b.nTable
            })
        };
        p.util = {
            throttle: ta,
            escapeRegex: ua
        };
        p.camelToHungarian = G;
        r("$()", function(a, b) {
            var c = this.rows(b).nodes(),
                c = g(c);
            return g([].concat(c.filter(a).toArray(), c.find(a).toArray()))
        });
        g.each(["on", "one", "off"], function(a, b) {
            r(b + "()", function() {
                var a = Array.prototype.slice.call(arguments);
                a[0].match(/\.dt\b/) || (a[0] += ".dt");
                var e = g(this.tables().nodes());
                e[b].apply(e, a);
                return this
            })
        });
        r("clear()", function() {
            return this.iterator("table", function(a) {
                na(a)
            })
        });
        r("settings()", function() {
            return new q(this.context, this.context)
        });
        r("data()", function() {
            return this.iterator("table",
                function(a) {
                    return C(a.aoData, "_aData")
                }).flatten()
        });
        r("destroy()", function(a) {
            a = a || !1;
            return this.iterator("table", function(b) {
                var c = b.nTableWrapper.parentNode,
                    e = b.oClasses,
                    d = b.nTable,
                    f = b.nTBody,
                    h = b.nTHead,
                    i = b.nTFoot,
                    j = g(d),
                    f = g(f),
                    l = g(b.nTableWrapper),
                    m = g.map(b.aoData, function(a) {
                        return a.nTr
                    }),
                    o;
                b.bDestroying = !0;
                u(b, "aoDestroyCallback", "destroy", [b]);
                a || (new q(b)).columns().visible(!0);
                l.unbind(".DT").find(":not(tbody *)").unbind(".DT");
                g(Da).unbind(".DT-" + b.sInstance);
                d != h.parentNode && (j.children("thead").detach(),
                    j.append(h));
                i && d != i.parentNode && (j.children("tfoot").detach(), j.append(i));
                j.detach();
                l.detach();
                b.aaSorting = [];
                b.aaSortingFixed = [];
                wa(b);
                g(m).removeClass(b.asStripeClasses.join(" "));
                g("th, td", h).removeClass(e.sSortable + " " + e.sSortableAsc + " " + e.sSortableDesc + " " + e.sSortableNone);
                b.bJUI && (g("th span." + e.sSortIcon + ", td span." + e.sSortIcon, h).detach(), g("th, td", h).each(function() {
                    var a = g("div." + e.sSortJUIWrapper, this);
                    g(this).append(a.contents());
                    a.detach()
                }));
                !a && c && c.insertBefore(d, b.nTableReinsertBefore);
                f.children().detach();
                f.append(m);
                j.css("width", b.sDestroyWidth).removeClass(e.sTable);
                (o = b.asDestroyStripes.length) && f.children().each(function(a) {
                    g(this).addClass(b.asDestroyStripes[a % o])
                });
                c = g.inArray(b, p.settings); - 1 !== c && p.settings.splice(c, 1)
            })
        });
        p.version = "1.10.4";
        p.settings = [];
        p.models = {};
        p.models.oSearch = {
            bCaseInsensitive: !0,
            sSearch: "",
            bRegex: !1,
            bSmart: !0
        };
        p.models.oRow = {
            nTr: null,
            anCells: null,
            _aData: [],
            _aSortData: null,
            _aFilterData: null,
            _sFilterRow: null,
            _sRowStripe: "",
            src: null
        };
        p.models.oColumn = {
            idx: null,
            aDataSort: null,
            asSorting: null,
            bSearchable: null,
            bSortable: null,
            bVisible: null,
            _sManualType: null,
            _bAttrSrc: !1,
            fnCreatedCell: null,
            fnGetData: null,
            fnSetData: null,
            mData: null,
            mRender: null,
            nTh: null,
            nTf: null,
            sClass: null,
            sContentPadding: null,
            sDefaultContent: null,
            sName: null,
            sSortDataType: "std",
            sSortingClass: null,
            sSortingClassJUI: null,
            sTitle: null,
            sType: null,
            sWidth: null,
            sWidthOrig: null
        };
        p.defaults = {
            aaData: null,
            aaSorting: [
                [0, "asc"]
            ],
            aaSortingFixed: [],
            ajax: null,
            aLengthMenu: [10, 25, 50, 100],
            aoColumns: null,
            aoColumnDefs: null,
            aoSearchCols: [],
            asStripeClasses: null,
            bAutoWidth: !0,
            bDeferRender: !1,
            bDestroy: !1,
            bFilter: !0,
            bInfo: !0,
            bJQueryUI: !1,
            bLengthChange: !0,
            bPaginate: !0,
            bProcessing: !1,
            bRetrieve: !1,
            bScrollCollapse: !1,
            bServerSide: !1,
            bSort: !0,
            bSortMulti: !0,
            bSortCellsTop: !1,
            bSortClasses: !0,
            bStateSave: !1,
            fnCreatedRow: null,
            fnDrawCallback: null,
            fnFooterCallback: null,
            fnFormatNumber: function(a) {
                return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g, this.oLanguage.sThousands)
            },
            fnHeaderCallback: null,
            fnInfoCallback: null,
            fnInitComplete: null,
            fnPreDrawCallback: null,
            fnRowCallback: null,
            fnServerData: null,
            fnServerParams: null,
            fnStateLoadCallback: function(a) {
                try {
                    return JSON.parse((-1 === a.iStateDuration ? sessionStorage : localStorage).getItem("DataTables_" + a.sInstance + "_" + location.pathname))
                } catch (b) {}
            },
            fnStateLoadParams: null,
            fnStateLoaded: null,
            fnStateSaveCallback: function(a, b) {
                try {
                    (-1 === a.iStateDuration ? sessionStorage : localStorage).setItem("DataTables_" + a.sInstance + "_" + location.pathname, JSON.stringify(b))
                } catch (c) {}
            },
            fnStateSaveParams: null,
            iStateDuration: 7200,
            iDeferLoading: null,
            iDisplayLength: 10,
            iDisplayStart: 0,
            iTabIndex: 0,
            oClasses: {},
            oLanguage: {
                oAria: {
                    sSortAscending: ": activate to sort column ascending",
                    sSortDescending: ": activate to sort column descending"
                },
                oPaginate: {
                    sFirst: "First",
                    sLast: "Last",
                    sNext: "Next",
                    sPrevious: "Previous"
                },
                sEmptyTable: "No data available in table",
                sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
                sInfoEmpty: "Showing 0 to 0 of 0 entries",
                sInfoFiltered: "(filtered from _MAX_ total entries)",
                sInfoPostFix: "",
                sDecimal: "",
                sThousands: ",",
                sLengthMenu: "Show _MENU_ entries",
                sLoadingRecords: "Loading...",
                sProcessing: "Processing...",
                sSearch: "Search:",
                sSearchPlaceholder: "",
                sUrl: "",
                sZeroRecords: "No matching records found"
            },
            oSearch: g.extend({}, p.models.oSearch),
            sAjaxDataProp: "data",
            sAjaxSource: null,
            sDom: "lfrtip",
            searchDelay: null,
            sPaginationType: "simple_numbers",
            sScrollX: "",
            sScrollXInner: "",
            sScrollY: "",
            sServerMethod: "GET",
            renderer: null
        };
        V(p.defaults);
        p.defaults.column = {
            aDataSort: null,
            iDataSort: -1,
            asSorting: ["asc", "desc"],
            bSearchable: !0,
            bSortable: !0,
            bVisible: !0,
            fnCreatedCell: null,
            mData: null,
            mRender: null,
            sCellType: "td",
            sClass: "",
            sContentPadding: "",
            sDefaultContent: null,
            sName: "",
            sSortDataType: "std",
            sTitle: null,
            sType: null,
            sWidth: null
        };
        V(p.defaults.column);
        p.models.oSettings = {
            oFeatures: {
                bAutoWidth: null,
                bDeferRender: null,
                bFilter: null,
                bInfo: null,
                bLengthChange: null,
                bPaginate: null,
                bProcessing: null,
                bServerSide: null,
                bSort: null,
                bSortMulti: null,
                bSortClasses: null,
                bStateSave: null
            },
            oScroll: {
                bCollapse: null,
                iBarWidth: 0,
                sX: null,
                sXInner: null,
                sY: null
            },
            oLanguage: {
                fnInfoCallback: null
            },
            oBrowser: {
                bScrollOversize: !1,
                bScrollbarLeft: !1
            },
            ajax: null,
            aanFeatures: [],
            aoData: [],
            aiDisplay: [],
            aiDisplayMaster: [],
            aoColumns: [],
            aoHeader: [],
            aoFooter: [],
            oPreviousSearch: {},
            aoPreSearchCols: [],
            aaSorting: null,
            aaSortingFixed: [],
            asStripeClasses: null,
            asDestroyStripes: [],
            sDestroyWidth: 0,
            aoRowCallback: [],
            aoHeaderCallback: [],
            aoFooterCallback: [],
            aoDrawCallback: [],
            aoRowCreatedCallback: [],
            aoPreDrawCallback: [],
            aoInitComplete: [],
            aoStateSaveParams: [],
            aoStateLoadParams: [],
            aoStateLoaded: [],
            sTableId: "",
            nTable: null,
            nTHead: null,
            nTFoot: null,
            nTBody: null,
            nTableWrapper: null,
            bDeferLoading: !1,
            bInitialised: !1,
            aoOpenRows: [],
            sDom: null,
            searchDelay: null,
            sPaginationType: "two_button",
            iStateDuration: 0,
            aoStateSave: [],
            aoStateLoad: [],
            oSavedState: null,
            oLoadedState: null,
            sAjaxSource: null,
            sAjaxDataProp: null,
            bAjaxDataGet: !0,
            jqXHR: null,
            json: l,
            oAjaxData: l,
            fnServerData: null,
            aoServerParams: [],
            sServerMethod: null,
            fnFormatNumber: null,
            aLengthMenu: null,
            iDraw: 0,
            bDrawing: !1,
            iDrawError: -1,
            _iDisplayLength: 10,
            _iDisplayStart: 0,
            _iRecordsTotal: 0,
            _iRecordsDisplay: 0,
            bJUI: null,
            oClasses: {},
            bFiltered: !1,
            bSorted: !1,
            bSortCellsTop: null,
            oInit: null,
            aoDestroyCallback: [],
            fnRecordsTotal: function() {
                return "ssp" == A(this) ? 1 * this._iRecordsTotal : this.aiDisplayMaster.length
            },
            fnRecordsDisplay: function() {
                return "ssp" == A(this) ? 1 * this._iRecordsDisplay : this.aiDisplay.length
            },
            fnDisplayEnd: function() {
                var a = this._iDisplayLength,
                    b = this._iDisplayStart,
                    c = b + a,
                    e = this.aiDisplay.length,
                    d = this.oFeatures,
                    f = d.bPaginate;
                return d.bServerSide ? !1 === f || -1 === a ? b + e :
                    Math.min(b + a, this._iRecordsDisplay) : !f || c > e || -1 === a ? e : c
            },
            oInstance: null,
            sInstance: null,
            iTabIndex: 0,
            nScrollHead: null,
            nScrollFoot: null,
            aLastSort: [],
            oPlugins: {}
        };
        p.ext = w = {
            classes: {},
            errMode: "alert",
            feature: [],
            search: [],
            internal: {},
            legacy: {
                ajax: null
            },
            pager: {},
            renderer: {
                pageButton: {},
                header: {}
            },
            order: {},
            type: {
                detect: [],
                search: {},
                order: {}
            },
            _unique: 0,
            fnVersionCheck: p.fnVersionCheck,
            iApiIndex: 0,
            oJUIClasses: {},
            sVersion: p.version
        };
        g.extend(w, {
            afnFiltering: w.search,
            aTypes: w.type.detect,
            ofnSearch: w.type.search,
            oSort: w.type.order,
            afnSortData: w.order,
            aoFeatures: w.feature,
            oApi: w.internal,
            oStdClasses: w.classes,
            oPagination: w.pager
        });
        g.extend(p.ext.classes, {
            sTable: "ow_vote_dataTable",
            sNoFooter: "no-footer",
            sPageButton: "paginate_button",
            sPageButtonActive: "current",
            sPageButtonDisabled: "disabled",
            sStripeOdd: "odd",
            sStripeEven: "even",
            sRowEmpty: "ow_vote_dataTables_empty",
            sWrapper: "ow_vote_dataTables_wrapper",
            sFilter: "ow_vote_dataTables_filter",
            sInfo: "ow_vote_dataTables_info",
            sPaging: "ow_vote_dataTables_paginate paging_",
            sLength: "ow_vote_dataTables_length",
            sProcessing: "ow_vote_dataTables_processing",
            sSortAsc: "sorting_asc",
            sSortDesc: "sorting_desc",
            sSortable: "sorting",
            sSortableAsc: "sorting_asc_disabled",
            sSortableDesc: "sorting_desc_disabled",
            sSortableNone: "sorting_disabled",
            sSortColumn: "sorting_",
            sFilterInput: "",
            sLengthSelect: "",
            sScrollWrapper: "ow_vote_dataTables_scroll",
            sScrollHead: "ow_vote_dataTables_scrollHead",
            sScrollHeadInner: "ow_vote_dataTables_scrollHeadInner",
            sScrollBody: "ow_vote_dataTables_scrollBody",
            sScrollFoot: "ow_vote_dataTables_scrollFoot",
            sScrollFootInner: "ow_vote_dataTables_scrollFootInner",
            sHeaderTH: "",
            sFooterTH: "",
            sSortJUIAsc: "",
            sSortJUIDesc: "",
            sSortJUI: "",
            sSortJUIAscAllowed: "",
            sSortJUIDescAllowed: "",
            sSortJUIWrapper: "",
            sSortIcon: "",
            sJUIHeader: "",
            sJUIFooter: ""
        });
        var Ca = "",
            Ca = "",
            E = Ca + "ui-state-default",
            ia = Ca + "css_right ui-icon ui-icon-",
            Wb = Ca + "fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix";
        g.extend(p.ext.oJUIClasses, p.ext.classes, {
            sPageButton: "fg-button ui-button " + E,
            sPageButtonActive: "ui-state-disabled",
            sPageButtonDisabled: "ui-state-disabled",
            sPaging: "ow_vote_dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_",
            sSortAsc: E + " sorting_asc",
            sSortDesc: E + " sorting_desc",
            sSortable: E + " sorting",
            sSortableAsc: E + " sorting_asc_disabled",
            sSortableDesc: E + " sorting_desc_disabled",
            sSortableNone: E + " sorting_disabled",
            sSortJUIAsc: ia + "triangle-1-n",
            sSortJUIDesc: ia + "triangle-1-s",
            sSortJUI: ia + "carat-2-n-s",
            sSortJUIAscAllowed: ia + "carat-1-n",
            sSortJUIDescAllowed: ia + "carat-1-s",
            sSortJUIWrapper: "DataTables_sort_wrapper",
            sSortIcon: "DataTables_sort_icon",
            sScrollHead: "ow_vote_dataTables_scrollHead " + E,
            sScrollFoot: "ow_vote_dataTables_scrollFoot " + E,
            sHeaderTH: E,
            sFooterTH: E,
            sJUIHeader: Wb + " ui-corner-tl ui-corner-tr",
            sJUIFooter: Wb + " ui-corner-bl ui-corner-br"
        });
        var Lb = p.ext.pager;
        g.extend(Lb, {
            simple: function() {
                return ["previous", "next"]
            },
            full: function() {
                return ["first", "previous", "next", "last"]
            },
            simple_numbers: function(a, b) {
                return ["previous", Va(a, b), "next"]
            },
            full_numbers: function(a, b) {
                return ["first", "previous", Va(a, b), "next", "last"]
            },
            _numbers: Va,
            numbers_length: 7
        });
        g.extend(!0, p.ext.renderer, {
            pageButton: {
                _: function(a, b, c, e, d, f) {
                    var h = a.oClasses,
                        i =
                        a.oLanguage.oPaginate,
                        j, l, m = 0,
                        o = function(b, e) {
                            var k, p, r, q, s = function(b) {
                                Sa(a, b.data.action, true)
                            };
                            k = 0;
                            for (p = e.length; k < p; k++) {
                                q = e[k];
                                if (g.isArray(q)) {
                                    r = g("<" + (q.DT_el || "div") + "/>").appendTo(b);
                                    o(r, q)
                                } else {
                                    l = j = "";
                                    switch (q) {
                                        case "ellipsis":
                                            b.append("<span>&hellip;</span>");
                                            break;
                                        case "first":
                                            j = i.sFirst;
                                            l = q + (d > 0 ? "" : " " + h.sPageButtonDisabled);
                                            break;
                                        case "previous":
                                            j = i.sPrevious;
                                            l = q + (d > 0 ? "" : " " + h.sPageButtonDisabled);
                                            break;
                                        case "next":
                                            j = i.sNext;
                                            l = q + (d < f - 1 ? "" : " " + h.sPageButtonDisabled);
                                            break;
                                        case "last":
                                            j =
                                                i.sLast;
                                            l = q + (d < f - 1 ? "" : " " + h.sPageButtonDisabled);
                                            break;
                                        default:
                                            j = q + 1;
                                            l = d === q ? h.sPageButtonActive : ""
                                    }
                                    if (j) {
                                        r = g("<a>", {
                                            "class": h.sPageButton + " " + l,
                                            "aria-controls": a.sTableId,
                                            "data-dt-idx": m,
                                            tabindex: a.iTabIndex,
                                            id: c === 0 && typeof q === "string" ? a.sTableId + "_" + q : null
                                        }).html(j).appendTo(b);
                                        Ua(r, {
                                            action: q
                                        }, s);
                                        m++
                                    }
                                }
                            }
                        };
                    try {
                        var k = g(P.activeElement).data("dt-idx");
                        o(g(b).empty(), e);
                        k !== null && g(b).find("[data-dt-idx=" + k + "]").focus()
                    } catch (p) {}
                }
            }
        });
        g.extend(p.ext.type.detect, [function(a, b) {
            var c = b.oLanguage.sDecimal;
            return Ya(a, c) ? "num" + c : null
        }, function(a) {
            if (a && !(a instanceof Date) && (!$b.test(a) || !ac.test(a))) return null;
            var b = Date.parse(a);
            return null !== b && !isNaN(b) || H(a) ? "date" : null
        }, function(a, b) {
            var c = b.oLanguage.sDecimal;
            return Ya(a, c, !0) ? "num-fmt" + c : null
        }, function(a, b) {
            var c = b.oLanguage.sDecimal;
            return Qb(a, c) ? "html-num" + c : null
        }, function(a, b) {
            var c = b.oLanguage.sDecimal;
            return Qb(a, c, !0) ? "html-num-fmt" + c : null
        }, function(a) {
            return H(a) || "string" === typeof a && -1 !== a.indexOf("<") ? "html" : null
        }]);
        g.extend(p.ext.type.search, {
            html: function(a) {
                return H(a) ? a : "string" === typeof a ? a.replace(Nb, " ").replace(Aa, "") : ""
            },
            string: function(a) {
                return H(a) ? a : "string" === typeof a ? a.replace(Nb, " ") : a
            }
        });
        var za = function(a, b, c, e) {
            if (0 !== a && (!a || "-" === a)) return -Infinity;
            b && (a = Pb(a, b));
            a.replace && (c && (a = a.replace(c, "")), e && (a = a.replace(e, "")));
            return 1 * a
        };
        g.extend(w.type.order, {
            "date-pre": function(a) {
                return Date.parse(a) || 0
            },
            "html-pre": function(a) {
                return H(a) ? "" : a.replace ? a.replace(/<.*?>/g, "").toLowerCase() : a + ""
            },
            "string-pre": function(a) {
                return H(a) ?
                    "" : "string" === typeof a ? a.toLowerCase() : !a.toString ? "" : a.toString()
            },
            "string-asc": function(a, b) {
                return a < b ? -1 : a > b ? 1 : 0
            },
            "string-desc": function(a, b) {
                return a < b ? 1 : a > b ? -1 : 0
            }
        });
        cb("");
        g.extend(!0, p.ext.renderer, {
            header: {
                _: function(a, b, c, e) {
                    g(a.nTable).on("order.dt.DT", function(d, f, h, g) {
                        if (a === f) {
                            d = c.idx;
                            b.removeClass(c.sSortingClass + " " + e.sSortAsc + " " + e.sSortDesc).addClass(g[d] == "asc" ? e.sSortAsc : g[d] == "desc" ? e.sSortDesc : c.sSortingClass)
                        }
                    })
                },
                jqueryui: function(a, b, c, e) {
                    g("<div/>").addClass(e.sSortJUIWrapper).append(b.contents()).append(g("<span/>").addClass(e.sSortIcon +
                        " " + c.sSortingClassJUI)).appendTo(b);
                    g(a.nTable).on("order.dt.DT", function(d, f, g, i) {
                        if (a === f) {
                            d = c.idx;
                            b.removeClass(e.sSortAsc + " " + e.sSortDesc).addClass(i[d] == "asc" ? e.sSortAsc : i[d] == "desc" ? e.sSortDesc : c.sSortingClass);
                            b.find("span." + e.sSortIcon).removeClass(e.sSortJUIAsc + " " + e.sSortJUIDesc + " " + e.sSortJUI + " " + e.sSortJUIAscAllowed + " " + e.sSortJUIDescAllowed).addClass(i[d] == "asc" ? e.sSortJUIAsc : i[d] == "desc" ? e.sSortJUIDesc : c.sSortingClassJUI)
                        }
                    })
                }
            }
        });
        p.render = {
            number: function(a, b, c, e) {
                return {
                    display: function(d) {
                        var f =
                            0 > d ? "-" : "",
                            d = Math.abs(parseFloat(d)),
                            g = parseInt(d, 10),
                            d = c ? b + (d - g).toFixed(c).substring(2) : "";
                        return f + (e || "") + g.toString().replace(/\B(?=(\d{3})+(?!\d))/g, a) + d
                    }
                }
            }
        };
        g.extend(p.ext.internal, {
            _fnExternApiFunc: Mb,
            _fnBuildAjax: qa,
            _fnAjaxUpdate: jb,
            _fnAjaxParameters: sb,
            _fnAjaxUpdateDraw: tb,
            _fnAjaxDataSrc: ra,
            _fnAddColumn: Ea,
            _fnColumnOptions: ja,
            _fnAdjustColumnSizing: X,
            _fnVisibleToColumnIndex: ka,
            _fnColumnIndexToVisible: $,
            _fnVisbleColumns: aa,
            _fnGetColumns: Z,
            _fnColumnTypes: Ga,
            _fnApplyColumnDefs: hb,
            _fnHungarianMap: V,
            _fnCamelToHungarian: G,
            _fnLanguageCompat: O,
            _fnBrowserDetect: fb,
            _fnAddData: I,
            _fnAddTr: la,
            _fnNodeToDataIndex: function(a, b) {
                return b._DT_RowIndex !== l ? b._DT_RowIndex : null
            },
            _fnNodeToColumnIndex: function(a, b, c) {
                return g.inArray(c, a.aoData[b].anCells)
            },
            _fnGetCellData: v,
            _fnSetCellData: Ha,
            _fnSplitObjNotation: Ja,
            _fnGetObjectDataFn: W,
            _fnSetObjectDataFn: Q,
            _fnGetDataMaster: Ka,
            _fnClearTable: na,
            _fnDeleteIndex: oa,
            _fnInvalidate: ca,
            _fnGetRowElements: ma,
            _fnCreateTr: Ia,
            _fnBuildHead: ib,
            _fnDrawHead: ea,
            _fnDraw: L,
            _fnReDraw: M,
            _fnAddOptionsHtml: lb,
            _fnDetectHeader: da,
            _fnGetUniqueThs: pa,
            _fnFeatureHtmlFilter: nb,
            _fnFilterComplete: fa,
            _fnFilterCustom: wb,
            _fnFilterColumn: vb,
            _fnFilter: ub,
            _fnFilterCreateSearch: Pa,
            _fnEscapeRegex: ua,
            _fnFilterData: xb,
            _fnFeatureHtmlInfo: qb,
            _fnUpdateInfo: Ab,
            _fnInfoMacros: Bb,
            _fnInitialise: ga,
            _fnInitComplete: sa,
            _fnLengthChange: Qa,
            _fnFeatureHtmlLength: mb,
            _fnFeatureHtmlPaginate: rb,
            _fnPageChange: Sa,
            _fnFeatureHtmlProcessing: ob,
            _fnProcessingDisplay: B,
            _fnFeatureHtmlTable: pb,
            _fnScrollDraw: Y,
            _fnApplyToChildren: F,
            _fnCalculateColumnWidths: Fa,
            _fnThrottle: ta,
            _fnConvertToWidth: Cb,
            _fnScrollingWidthAdjust: Eb,
            _fnGetWidestNode: Db,
            _fnGetMaxLenString: Fb,
            _fnStringToCss: s,
            _fnScrollBarWidth: Gb,
            _fnSortFlatten: T,
            _fnSort: kb,
            _fnSortAria: Ib,
            _fnSortListener: Ta,
            _fnSortAttachListener: Na,
            _fnSortingClasses: wa,
            _fnSortData: Hb,
            _fnSaveState: xa,
            _fnLoadState: Jb,
            _fnSettingsFromNode: ya,
            _fnLog: R,
            _fnMap: D,
            _fnBindAction: Ua,
            _fnCallbackReg: x,
            _fnCallbackFire: u,
            _fnLengthOverflow: Ra,
            _fnRenderer: Oa,
            _fnDataSource: A,
            _fnRowAttributes: La,
            _fnCalculateEnd: function() {}
        });
        g.fn.ow_vote_dataTable = p;
        g.fn.ow_vote_dataTableSettings = p.settings;
        g.fn.ow_vote_dataTableExt = p.ext;
        g.fn.DataTable = function(a) {
            return g(this).ow_vote_dataTable(a).api()
        };
        g.each(p, function(a, b) {
            g.fn.DataTable[a] = b
        });
        return g.fn.ow_vote_dataTable
    };
    "function" === typeof define && define.amd ? define("datatables", ["jquery"], O) : "object" === typeof exports ? O(require("jquery")) : jQuery && !jQuery.fn.ow_vote_dataTable && O(jQuery)
})(window, document);