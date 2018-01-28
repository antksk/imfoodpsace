<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>IMFOODSPACE</title>

    <?= $style_tag ?>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport"
          content="width=device-width, minimum-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- iphone address bar hidden -->
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Android address bar hidden -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>

    <!--  Android 5 Chrome Color-->
    <meta name="theme-color" content="#EE6E73">
</head>

<body>
<?= $script_tag ?>

<div class="navbar-fixed">
    <nav class="nav-extended">
        <div class="nav-wrapper">
            <a href="/imfs" class="button-collapse">
                <i class="material-icons">keyboard_arrow_left</i>
            </a>
            <a href="# " class="brand-logo"><?= $company->nm ?></a>

            <ul class="right hide-on-med-and-down">
                <li><a href="/imfs"><i class="material-icons">view_module</i></a></li>
                <!--
                        <li><a href="#!" class="dropdown-button" ><i class="material-icons">view_module</i></a></li>
						<li><a href="tel:<?= $company->tel_no ?>" class="button-collapse right "><i class="material-icons">phone</i></a></li>
						-->
            </ul>

            <!--
            <a href="#!" class="dropdown-button right" href="#!" data-activates="dropdownCategory"><i class="material-icons">view_module</i></a>
            -->
            <!--
            <form>
                <div class="input-field">
                    <input id="search" type="search" required>
                    <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
            -->

        </div>
    </nav>
    <!--
    <nav>
        <div class="nav-wrapper">
            <form>
                <div class="input-field">
                    <input id="search" type="search" required>
                    <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
        </div>
    </nav>
    -->
</div>

<ul id="dropdownCategory" class="dropdown-content">
    <li><a href="#!">one</a></li>
    <li><a href="#!">two</a></li>
    <li class="divider"></li>
    <li><a href="#!">three</a></li>
</ul>

<div style="padding-bottom: 50px;">
    <div id="detail" class="row ">

        <div class="col s12 m6 offset-m3">
            <div class="card">
                <div class="card-content">
                    <ul>
                        <li><?= $company->pd_nm ?></li>
                        <li><?= $company->addr_road ?> <?= $company->addr_detail ?></li>
                    </ul>
                </div>
                <div class="card-action">
                    <a href="tel:<?= $company->tel_no ?>"><i class="material-icons">phone</i></a>
                    <a href="mailto:<?= $company->email ?>"><i class="material-icons">email</i></a>
                    <a href="/imfs/estimate" id="cart"><i class="material-icons">shopping_cart</i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="display" class="col s12 m6 offset-m3">
            <ul class="category collection with-header">
                <? foreach ($merchandise_category->contents as $mc): ?>
                    <li class="collection-item">
                        <a href="#" data-href="/imfs/rest/category/mc/2/<?= $mc->id ?>" data-id="<?= $mc->id ?>"
                           data-parent="<?= $mc->parent_id ?>" data-leaf="<?= $mc->is_leaf ?>">
                            <?= $mc->nm ?>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>

        </div>
    </div>
</div>


<script id="category-template" type="text/x-handlebars-template">
    <ul class="category collection with-header">
        {{#each merchandises }}
        <li class="collection-item">
            <a href="#" data-href="{{url}}" data-id="{{id}}" data-parent="{{parent}}" data-leaf="{{leaf}}">
                {{nm}}
            </a>
        </li>
        {{/each}}
    </ul>
</script>

<script id="merchandise-template" type="text/x-handlebars-template">
    <table class="merchandise centered">
        <thead>
        <tr>
            <th data-field="name">품명</th>
            <th data-field="price">금액(원)</th>
            <th data-field="check">선택</th>
        </tr>
        </thead>
        <tbody>
        {{#each merchandises }}
        <tr data-id="{{id}}">
            <td><span class="truncate">{{nm}}</span><span>[{{head_unit}}]</span></td>
            <td>{{price_sale}}</td>
            <td>
                <input type="checkbox"
                       id="{{item_id}}"
                       value="{{id}}"
                       data-mcds='{{json_m}}'
                ><label for="{{item_id}}"></label></td>
        </tr>
        {{/each}}
        </tbody>
    </table>
</script>

<script type="text/javascript">
    // ! function(){

    function comma(value) {
        return String(value).replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }

    const ns = $.initNamespaceStorage('<?=$company_prefix . $company->b36_cd?>');
    const storage = ns.localStorage;//.setExpires(1);

    // 선택한 회사 정보 설정
    storage.set('_company', {
        id: <?=$company->id?>,
        com_nm: '<?=$company->nm?>',
        dtl: <?=json_encode($company)?>
    });


    // [template]
    const categoryTemplate = Handlebars.compile($('#category-template').html());
    const merchandiseTemplate = Handlebars.compile($('#merchandise-template').html());

    const displayLinkAction = function (e) {

        const $link = $(this);
        const isCategory = (false == $link.data('leaf'));
        console.log("LEAF", $link.data('leaf'));
        if (isCategory) {
            $.getJSON($link.data('href'), function (JSON) {
                const data = _(JSON.contents).map(e => {
                    const isLeaf = '1' === e.is_leaf;
                        return {
                            url: isLeaf ? `/imfs/rest/merchandise/m/<?=$company->id?>/${e.id}` : `/imfs/rest/category/mc/2/${e.id}`,
                            id: e.id,
                            parent: e.parent_id,
                            leaf: isLeaf,
                            nm: e.nm
                        };
                }).value();

                // alert( data );

                $('#display')
                    .html(categoryTemplate({merchandises: data}))
                    .find('.category a').click( displayLinkAction);
            });
            // $('#display').html();
        } else {
            $.getJSON($link.data('href'), function (json) {
                const data = _(json.contents).map(e => {

                    return {
                        nm: e.nm,
                        id: e.id,
                        item_id: e.item_id,
                        head_unit: e.head_unit,
                        json_m: JSON.stringify(e),
                        price_sale: comma(e.price_sale)
                    };
            }).
                value();
                $('#display')
                    .html(merchandiseTemplate({merchandises: data}));

                // 기존에 선택된 제품 정보 checkbox에 재 설정
                storage.keys().forEach((e) => $(`#${e}`).prop('checked', true)
            )
                ;

                // checkbox 이벤트와 storage 데이터 동기화
                $('input[data-mcds]').on('click', (e) => {
                    const $self = $(e.currentTarget);

                if ($self.prop('checked')) {
                    storage.set($self.attr('id'), $self.data('mcds'));
                } else {
                    storage.remove($self.attr('id'));
                }
            })
                ;
            });
        }
        return false;
    };

    $('#display .category a').click( displayLinkAction);

    // 등록한 최대 업체 수 _.size($.namespaceStorages);


    // }();
</script>

<?= $inc_common ?>

</body>
</html>