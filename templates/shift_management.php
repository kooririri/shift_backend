<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- タイトル以外のヘッダーの内容 -->
    <?php require "../templates/header_contents.php" ?>
    <title>見積もり登録</title>
</head>

<body>
<!-- ナビゲーションバー -->
<?php require "../templates/nav.php" ?>

<!-- ここにメインコンテンツ -->
<div id="input_forms" class="container p-5">
    <h2 class="text-center">見積もり登録</h2>
    <form action="./index.php" method="post">
        <div class="form-group">
            <label for="customer-select">顧客</label>
            <select class="form-control" id="customer-select" name="customer_id">
                <?php foreach ($view_customers as $customer): ?>
                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <div v-for="n in form_count" class="pt-5">
                <h3>{{n}}台目</h3>
                <fieldset class="form-group">
                    <div class="row">
                        <legend class="col-form-label col-sm-4 pt-0">買注文/売注文</legend>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-bind:name="`input_data[${n-1}][is_sell]`"
                                       v-bind:id="`buy${n-1}`" value="0"
                                       checked>
                                <label class="form-check-label" v-bind:for="`buy${n-1}`">
                                    買注文
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-bind:name="`input_data[${n-1}][is_sell]`"
                                       v-bind:id="`sell${n-1}`"
                                       value="1">
                                <label class="form-check-label" v-bind:for="`sell${n-1}`">
                                    売注文
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group row">
                    <label v-bind:for="`car_number${n-1}`" class="col-sm-4 col-form-label">型式番号</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" v-bind:id="`car_number${n-1}`"
                               v-bind:name="`input_data[${n-1}][car_number]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`status${n-1}`" class="col-sm-4 col-form-label">ステータス</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" v-bind:id="`status${n-1}`" v-bind:name="`input_data[${n-1}][status]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`color${n-1}`" class="col-sm-4 col-form-label">色</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" v-bind:id="`color${n-1}`" v-bind:name="`input_data[${n-1}][color]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`distance${n-1}`" class="col-sm-4 col-form-label">走行距離</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" v-bind:id="`distance${n-1}`"
                               v-bind:name="`input_data[${n-1}][distance]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`quantity${n-1}`" class="col-sm-4 col-form-label">数量</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" v-bind:id="`quantity${n-1}`"
                               v-bind:name="`input_data[${n-1}][quantity]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`estimate_price${n-1}`" class="col-sm-4 col-form-label">一台当たりの見積もり金額</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" v-bind:id="`estimate_price${n-1}`"
                               v-bind:name="`input_data[${n-1}][estimate_price]`">
                    </div>
                </div>
                <div class="form-group row">
                    <label v-bind:for="`input_data[${n-1}][fee]`" class="col-sm-4 col-form-label">手数料</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" v-bind:id="`fee${n-1}`" v-bind:name="`input_data[${n-1}][fee]`">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row ">
            <div class="col-sm-12">
                <button v-on:click="add_form_count()" type="button" class="float-left btn btn-info">
                    <i class="fa fa-plus"></i>
                </button>
                <button v-if="form_count > 1" v-on:click="minus_form_count()" type="button" class="float-left ml-3 btn btn-danger">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="submit" class="float-right btn btn-primary" name="submit">送信</button>
            </div>
        </div>
    </form>
</div>

<!-- JS読み込み -->
<?php require "../templates/scripts.php" ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script>
    new Vue({
        el: "#input_forms",
        data: {
            form_count: 1
        },
        methods: {
            add_form_count() {
                this.form_count++;
            },
            minus_form_count() {
                if (this.form_count > 0) {
                    this.form_count--;
                }
            }
        }
    })
</script>
</body>

</html>