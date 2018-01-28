<!-- cart -->
<div class="fixed-action-btn" style="width: 50px;">
	<a href="/imfs/estimate" style="margin-bottom: 10px;"
		class="btn-floating btn-large waves-effect waves-light red"><i
		class="material-icons">shopping_cart</i></a>
	<a id="helpTab" href="#"
			class="btn-floating btn-large waves-effect waves-light blue"><i
			class="material-icons">home</i></a>
</div>



<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<script type="text/javascript">

      // https://github.com/typekit/webfontloader
    WebFont.load({

        // For google fonts
        google: {
          families: ['Baloo Tamma']
        },
        // For early access or custom font
        custom: { families: ['Nanum Gothic'],
          urls:['http://fonts.googleapis.com/earlyaccess/nanumgothic.css']
        }

    });

    $('#imfsLogo, #helpTab').on('click',function(e){
        $('ul.tabs').tabs('select_tab', 'help');
        return false;
    });

</script>