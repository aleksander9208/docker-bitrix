{"version":3,"sources":["sequentialworkflowactivity.js"],"names":["SequentialWorkflowActivity","ob","SequenceActivity","Type","swfWorkspaceDiv","swfToolBoxDiv","Table","hSnippid1","onScroll","p","BX","d","hasClass","panelSize","pos","height","scrollSize","GetWindowInnerSize","posW","innerHeight","rows","cells","scrollPos","GetWindowScrollPos","scrollTop","top","swfToolboxDiv","style","position","overflowY","width","left","scrollLeft","bottom","DrawSequenceActivity","Draw","div","window","dlgSnippetsSettings","CDialog","content","BPMESS","title","buttons","message","id","name","action","r","__lastSnippet","ind","_ind","arUserParams","value","childNodes","innerHTML","HTMLEncode","document","getElementById","this","parentWindow","Close","BCPSaveUserParams","prototype","btnCancel","setTimeout","onclick","confirm","splice","allP","parentNode","remove","i","length","appendChild","_crt","onscroll","onresize","DragNDrop","AddHandler","ondragging2","hSnippid2","ondrop2","createElement","className","parseInt","clientHeight","ShowActivities","_table","vAlign","align","begin","margin","textAlign","DrawActivities","end","removeChild","DrawGroup","oGroup","divGroup","swftoolboxid","e","divGroupHeader","divGroupList","DrawGroupItem","oActivity","bExt","dCont","bCat","cat","PreventDefault","undefined","activityTemplate","Properties","Title","Children","Icon","t","arAllActivities","toLowerCase","background","cursor","fontSize","onmousedown","Show","insertRow","insertCell","colSpan","event","StartDrag","offsetWidth","DrawMarketplaceItem","rest","Marketplace","open","overflowX","groupId","arAllActGroups","act_i","hasOwnProperty","activityGroupId","rootActivity","getClass","drdrop","divSnippets","cntmp","padding","color","border","isn","lastDrop2","X","Y","right","CreateActivity","obj","push","Serialize","RemoveResourcesSequenceActivity","RemoveResources","RemoveHandler"],"mappings":"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","file":"sequentialworkflowactivity.map.js"}