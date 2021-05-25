
	$.fn.extend({
		comboInit:function(callback) {
			if(callback==undefined) callback=function(){};
			var dv=$("<div></div>").text("");
			var nCmb=$(this);
			if($(this).attr('size') =='large') var minWidth=450; else minWidth=250;
			var wd=$(this).width()<minWidth ? minWidth: $(this).width();

			var mg=$(this).css('margin-bottom');
			$(dv).addClass("z-depth-1 collection white capitalize").css({"position":"absolute","width":wd,'z-index':100,'margin-top':'-'+mg,'max-height':'150px','overflow-y':'auto'}).attr({'id':'_combo'}).hide();
			$(this).parent().append(dv);
			$(this).dv=$(dv);
			loadCombo($(this),callback);
			$(this).keyup(function(e) {
				var dv=$(this).parent().find('#_combo');
				$(dv).fadeIn();
				if(e.which== 13)
				{
					var crr=$(this).attr('data-current');
					if(crr == -1 || undefined)
					{
						var pagetype=$(this).attr('data-type');
						addNewData($(this),$(dv),pagetype)
					} else {
						$(dv).children(':eq('+crr+')').click();
						$(dv).fadeOut();
					}
				}else if(e.which==38)
				{
					var crr=$(this).attr('data-current');
					crr--;
					if(crr < 0) crr= 0;
					$(dv).children().removeClass('active');
					$(dv).children(':eq('+crr+')').addClass('active');
					$(this).attr({'data-current':crr});

				}else if(e.which==40)
				{
					var crr=$(this).attr('data-current');
					crr++;
					if(crr > $(dv).children().length) crr= $(dv).children().length
					$(dv).children().removeClass('active');

					$(dv).children(':eq('+crr+')').addClass('active');

					$(this).attr({'data-current':crr});

				}else loadCombo($(this),callback)


			});
			$(this).blur(function() {
				var dv=$(this).parent().find('#_combo');
				$(dv).fadeOut();
			})
			$(this).focus(function() {
				var wd=$(this).width()<minWidth ? minWidth: $(this).width();
				var dv=$(this).parent().find('#_combo');				 				$(dv).fadeIn().css({'width':wd});
			})
			var chd=$('<div></div>').addClass('combo_hold');
			var dataname=$(this).attr('name');
			$(chd).prepend($('<input></input>').css({"display":"none"}).attr({"name":dataname,"type":"hidden"}));
			$(this).parent().prepend($(chd));
		}

	});
	function loadCombo(p,callback)
	{
		var pagetype=$(p).attr('data-type');
		$.getJSON("processAjax.php?pageType="+pagetype+"&p=0&l=4&search="+$(p).val(), function(result){
			var npD=$(p).parent().find('#_combo');
			$(npD).html("")
			$(p).attr({'data-current':-1});
			if(result.row ==undefined || result.row.lenght==0)
			{
				var err=$('<div>').addClass('center').appendTo($(npD)).html('No result found<br><br>').css({'padding':'1%'});
				if($(p).attr('data-create') != undefined && $(p).val().trim() !="")
				{
					$('<a>').html('Add "'+$(p).val()+'"').addClass('btn').appendTo(err).attr({'href':'javascript:;'}).click(function(){
		        addNewData(p,$(this).parent(),pagetype)
					});
				}
				return 0;
			}
			$.each(result.row, function(i, field){
					createComboRow(p,result,npD,field,callback)
			});
			if(result.row.length == 1)
			{
			 	if(result.row[0].c[0] == $(p).val())
				{
					var dv=$(p).parent().find('#_combo');
					$(dv).fadeOut();
					field=result.row[0];
					$(p).next().removeClass('active').addClass('active');
					$(p).val(field.c[0]).attr({'data_value':field.i,'data-label':field.c[0]}); //callback(p,field);

				}
			}

		});
	}
	function addNewData(p,npD,pagetype)
	{
		$.post('processAjax.php',{'pageType':pagetype,'new':$(p).val()},function(result){
				$(npD).html("");
				createComboRow(p,npD,{"i":result,"c":[{'v':$(p).val(),'f':'b'}]})
		});
	}
	function createComboRow(p,result,npD,field,callback)
	{
		var order=result.fmt;
		var nDiv=$("<a>").addClass("collection-item").attr({"id":field.i,'href':'javascript:;'});
		$(nDiv).click(function(){
			//console.log(result);
			$(p).next().removeClass('active').addClass('active');
			console.log(field);
			$(p).val(field.c[1]).attr({'data_value':field.i});
			// $(p).val(field.c[1]).attr({'data_value':field.i, 'data-label':field.c[0]});

			field.col = result.col;
			callback(p,field);

		})
		$.each(field.c, function(j, col){
			var vt = "";
			if(order[j]=="t")
			{
				nDiv.append(col +" ");
			} else if (order[j]=="span")
			{
				nDiv.append($("<span>").text(col + " "));
			} else if (order[j]=="b")
			{
				nDiv.append($("<span>").text(col + " "));
			}else if (order[j]=="a")
			{
				nDiv.append(col+ " ");
				vt+=col;
				nDiv.prop("dName",vt)
			}// else nDiv.append(col +" ");

		});
		$(npD).append(nDiv);

	}
	$(document).ready( function()
	{

		$('.combo').comboInit();


	} )
// JavaScript Document
