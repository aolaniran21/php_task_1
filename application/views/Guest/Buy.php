<style>
    .select2-container .select2-selection--single {
        height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 33px !important;
    }

    #country-list {
        float: left;
        list-style: none;
        margin-top: -3px;
        padding: 0;
        width: 190px;
        position: absolute;
    }

    #country-list li {
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #bbb9b9 1px solid;
    }

    #country-list li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    #search-box {
        border: none;
        margin-top: -4.5px;
        height: 33px;
    }

    .review_btn {
        cursor: pointer;
    }

    .download_btn {
        cursor: pointer;
    }

    table thead th {
        width: 180px;
    }

    #card-number {
        z-index: 0;
        height: 38px;
        border-radius: 2px;
        border: solid 1px #c4cbe1;
        font-size: 12px;
        color: #333;
        padding: 10px 15px;
        margin-bottom: 1rem;
    }

    #card-expiry {
        z-index: 0;
        height: 38px;
        border-radius: 2px;
        border: solid 1px #c4cbe1;
        font-size: 12px;
        color: #333;
        padding: 10px 15px;
        margin-bottom: 1rem;
    }

    #card-cvc {
        z-index: 0;
        height: 38px;
        border-radius: 2px;
        border: solid 1px #c4cbe1;
        font-size: 12px;
        color: #333;
        padding: 10px 15px;
        margin-bottom: 1rem;
    }

    form#form_filter a#reset {
        background: #343a40;
        width: 100% !important;
        color: #FFF;
        display: block;
        text-align: center;
        padding: 6px 20px;
    }

    form#form_filter .row .col-sm-8 .row {
        display: table;
        margin: 0 auto;
        width: 100%;
    }

    form#form_filter .row .col-sm-8 .row .col-md-2 {
        float: left;
        width: 20% !important;
        max-width: 20% !important;
    }

    form#form_filter .row .col-sm-8 .row .col-md-2 select,
    form#form_filter .row .col-sm-8 .row .col-md-2 span {
        width: 100% !important;
        max-width: 100% !important;
    }

    form#form_filter .row .col-sm-8 .row .col-md-2 span.select2-selection__arrow b {
        left: 94%;
        top: 17px;
    }

    @media screen and (max-width: 767px) {
        form#form_filter .row .col-sm-8 .row .col-md-2 {
            width: 100% !important;
            max-width: 100% !important;
            margin-bottom: 10px;
        }

    }
</style>
<?php

$school_id      = (isset($_GET['school_id']) ? $_GET['school_id'] : '');
$class_id       = (isset($_GET['class_id']) ? $_GET['class_id'] : '');
$professor_id   = (isset($_GET['professor_id']) ? $_GET['professor_id'] : '');
$isbn           = (isset($_GET['isbn']) ? $_GET['isbn'] : '');
$year           = (isset($_GET['year']) ? $_GET['year'] : '');


$filters = "school_id=" . $school_id . "&class_id=" . $class_id . "&professor_id=" . $professor_id . "&isbn=" . $isbn . "&year=" . $year . "&";

?>
<div class="container-fluid jumbotron" style="min-height:99px;padding: 2rem 1rem;">
    <form autocomplete="off" id="form_filter" action=" <?php echo base_url(); ?>buy" method="get">

        <input type="hidden" value="<?php echo isset($_GET['order_by']) ? $_GET['order_by'] : ''; ?>" name="order_by">
        <input type="hidden" value="<?php echo isset($_GET['direction']) ? $_GET['direction'] : ''; ?>" name="direction">
        <div class="row">
            <div class="col-md-12 col-sm-2"></div>
            <div class="col-md-12 col-sm-8">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <select name="school_id" class="custom-select trigger_change_event get_schools_select2 ">
                            <option value="0">Select School</option>
                            <?php if (isset($this->_data['school_data'])) {
                                foreach ($this->_data['school_data']  as $key => $value) {
                            ?>
                                    <option <?php echo ($_GET['school_id'] ?? 0) == $value->id ? 'selected' : ''; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">

                        <select name="class_id" class="custom-select trigger_change_event get_courses_select2 ">
                            <option value="0">Select Course</option>
                            <?php if (isset($this->_data['classes_data'])) {
                                foreach ($this->_data['classes_data']  as $key => $value) {
                            ?>
                                    <option <?php echo ($_GET['class_id'] ?? 0) == $value->id ? 'selected' : ''; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                            <?php }
                            } ?>
                        </select>

                    </div>
                    <div class="col-md-2 col-sm-12">

                        <select name="professor_id" class="custom-select trigger_change_event get_professors_select2 ">
                            <option value="0">Select Professor</option>
                            <?php if (isset($this->_data['professor_data'])) {
                                foreach ($this->_data['professor_data']  as $key => $value) {
                            ?>
                                    <option <?php echo ($_GET['professor_id'] ?? 0) == $value->id ? 'selected' : ''; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                            <?php }
                            } ?>
                        </select>

                    </div>
                    <div class="col-md-2 col-sm-12">

                        <select name="isbn" class="custom-select trigger_change_event get_textbooks_select2 ">
                            <option value="0">Select Textbook</option>
                            <?php if (isset($this->_data['textbook_data'])) {
                                foreach ($this->_data['textbook_data']  as $key => $value) {
                                    if (isset($value['isbn'])) { ?>
                                        <option <?php echo ($_GET['isbn'] ?? '') == $value['isbn'] ? 'selected' : ''; ?> value="<?php echo $value['isbn']; ?>"><?php echo $value['isbn']; ?></option>
                            <?php }
                                }
                            } ?>
                        </select>

                    </div>
                    <div class="col-md-2 col-sm-12">

                        <select name="year" class="custom-select trigger_change_event get_years_select2 ">
                            <option value="0">Select Year</option>
                            <?php if (isset($this->_data['year'])) {
                                foreach ($this->_data['year']  as $key => $value) {
                            ?>
                                    <option <?php echo ($_GET['year'] ?? '') == $value['year'] ? 'selected' : ''; ?> value="<?php echo $value['year']; ?>"><?php echo $value['year']; ?></option>
                            <?php
                                }
                            } ?>
                        </select>

                    </div>
                </div>
                <div class="row mt-2">
                    <!-- <div class="col-md-2 col-sm-2"> -->
                    <a href=" <?php echo base_url(); ?>buy" id="reset">Reset Filters</a>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="container table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>School <a style="color: white;" href="?<?php echo $filters ?>order_by=school_id&direction=<?php echo (($_GET['order_by'] ?? '') == 'school_id') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'school_id') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th>Course <a style="color: white;" href="?<?php echo $filters ?>order_by=class_id&direction=<?php echo (($_GET['order_by'] ?? '') == 'class_id') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'class_id') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th>Professor <a style="color: white;" href="?<?php echo $filters ?>order_by=professor_id&direction=<?php echo (($_GET['order_by'] ?? '') == 'professor_id') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'professor_id') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th>Year <a style="color: white;" href="?<?php echo $filters ?>order_by=year&direction=<?php echo (($_GET['order_by'] ?? '') == 'year') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'year') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th style=" min-width: 120px;"> Textbook <a style="color: white;" href="?<?php echo $filters ?>order_by=textbook_id&direction=<?php echo (($_GET['order_by'] ?? '') == 'textbook_id') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'textbook_id') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th>Word Count <a style="color: white;" href="?<?php echo $filters ?>order_by=word_count&direction=<?php echo (($_GET['order_by'] ?? '') == 'word_count') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'word_count') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>
                            <th style=" min-width: 170px; ">Type <a style="color: white;" href="?<?php echo $filters ?>order_by=note_type&direction=<?php echo (($_GET['order_by'] ?? '') == 'note_type') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'note_type') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>

                            <th>Rating <a style="color: white;" href="?<?php echo $filters ?>order_by=review&direction=<?php echo (($_GET['order_by'] ?? '') == 'review') ?  ($_GET['direction'] == 'DESC' ? 'ASC' : 'DESC') : 'ASC'; ?>"><i class="fa fa-sort<?php echo (($_GET['order_by'] ?? '') == 'review') ?  ($_GET['direction'] == 'DESC' ? '-down' : '-up') : ''; ?>"></i></a></th>

                            <th>Preview / Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($this->_data['items'])) {
                            foreach ($this->_data['items'] as $key => $value) {
                        ?>
                                <tr>
                                    <td><?php echo $value->school_name; ?></td>
                                    <td><?php echo $value->class_name; ?></td>
                                    <td><?php echo $value->professor_name; ?></td>
                                    <td><?php echo $value->year; ?></td>
                                    <td><?php echo $value->isbn == "" ? 'Assigned Text' : $value->isbn; ?></td>
                                    <td><?php echo $value->word_count; ?></td>
                                    <td><?php echo $value->note_type == 1 ? 'Outline' : 'Lecture Note'; ?></td>
                                    <!-- <td><?php //echo $value->first_name;
                                                ?></td> -->
                                    <td><?php echo $value->rating_count > 0 ? round($value->rating / $value->rating_count, 2) . '/10 <br>  <a class="review_btn" data-id="' . $value->id . '"><u>Reviews</u></a>'  : 'No Ratings Available'; ?></td>

                                    <td>
                                        <a href="/preview/<?php echo $value->id; ?>" target="_blank"><u>Preview</u></a>
                                        <?php if (!in_array($value->id, $this->_data['user_downloaded_files'])) { ?>

                                            <a href="#" data-toggle="modal" class="download_btn" id="p_<?php echo $value->id; ?>" data-id="<?php echo $value->id; ?>" data-target="#<?php echo !($this->session->userdata('user_id')) ? 'loginModal' : 'purchaseModal'; ?>"><u>Download <i class="fa fa-file-word-o"> </i></u></a>

                                        <?php } else {
                                            echo 'Already Downloaded';
                                        } ?>


                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr><td colspan="10">No Data Found</td></tr>';
                        } ?>

                    </tbody>
                </table>
                <p><?php echo  $this->_data['links'] ?? ''; ?></p>
            </div>

        </div>
        <!-- <div class="col-md-12 col-sm-1"></div> -->
    </div>
</div>
<!-- book_table_ends -->

<!-- login_modal_start -->
<!-- Modal -->
<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <h4 class="modal-title float-left">Login</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="<?php echo base_url(); ?>member/login" method="post">
                        <div class="form-group">
                            <input type="hidden" name="c_is_active" value="f86e789d983d0f9863a6766f473b8b28">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" placeholder="Enter email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
                        </div>
                        <input name="return_url" type="hidden" value="buy" />
                        <div class="form-group row">
                            <input type="hidden" name="return_url" value="buy" />
                            <div class="col-md-6 col-md-6 col-sm-6">
                                <a href="member/forgot" class="">Forgot Password</a>
                            </div>
                            <div class="col-md-6 col-md-6 col-sm-6">
                                <a href="member/register?return_url=buy" id="register_btn" class=" pull-right">Register</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="submit" style="margin-left: auto;margin-right:auto;" name="btn-login" class="btn btn-primary col-md-8" value="Login" />
                        </div>
                        <br />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- login_modal_end -->



<!-- register_modal_start -->
<!-- Modal -->
<div class="modal fade" id="registerModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">

                <h4 class="modal-title float-left">Register</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form>

                        <div class="form-group">
                            <label for="username">User Name:</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter username" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" placeholder="Enter password" name="pwd">
                        </div>
                        <div class="form-group ">
                            <span>Already have an Account?</span>
                            <a href="#"> Login</a>

                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="remember"> I agree to Terms and Conditions <a href="#"><u>here</u></a></label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="remember"> I agree to Privacy Policy <a href="#"><u>here</u></a></label>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
<!-- register_modal_end -->




<!-- purchase_modal_start -->
<div class="modal fade" id="purchaseModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <h5 class="modal-title float-left">Purchase</h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="form-group">
                        <h5 class="primaryHeading2 ">
                            Price $<?php echo $this->_data['notes_amount']; ?>.00
                        </h5>
                    </div>
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="card-holder-name" placeholder="Enter name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="name">Card Number <span class="text-danger">*</span></label>
                        <input class="InputElement Input form-control" autocomplete="cc-number" autocorrect="off" spellcheck="false" type="text" id="cardnumber" name="cardnumber" data-elements-stable-field-name="cardNumber" inputmode="numeric" aria-label="Credit or debit card number" placeholder="1234 1234 1234 1234" aria-invalid="true">
                    </div>

                    <div class="form-group">
                        <label for="month">Card Expiry Month <span class="required-must">*</span></label>
                        <select name="exp_month" id="exp_month" class="form-control">
                            <option value="">Select Month</option>
                            <option value="01">01 - January</option>
                            <option value="02">02 - February</option>
                            <option value="03">03 - March</option>
                            <option value="04">04 - April</option>
                            <option value="05">05 - May</option>
                            <option value="06">06 - June</option>
                            <option value="07">07 - July</option>
                            <option value="08">08 - August</option>
                            <option value="09">09 - September</option>
                            <option value="10">10 - October</option>
                            <option value="11">11 - November</option>
                            <option value="12">12 - December</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">Card Expiry Year<span class="text-danger">*</span></label>

                        <?php
                        $year  = Date('Y');
                        $limit = $year + 25;
                        ?>
                        <select name="exp_year" id="exp_year" class="form-control">
                            <option value="">Select Year</option>
                            <?php for ($i = $year; $i <= $limit; $i++) {
                                echo "<option value='" . $i . "' > " . $i . " </option>";
                            } ?>
                        </select>

                    </div>


                    <div class="form-group">
                        <label for="name">Card CVC <span class="text-danger">*</span></label>
                        <input class="InputElement is-empty Input Input--empty form-control" autocomplete="cc-csc" autocorrect="off" spellcheck="false" type="text" id="cvc" name="cvc" data-elements-stable-field-name="cardCvc" inputmode="numeric" aria-label="Credit or debit card CVC/CVV" placeholder="CVC" aria-invalid="false" value="">
                    </div>



                    <div class="checkbox">
                        <label><input type="checkbox" name="remember" id="remember"> I agree to Terms and Conditions <a href="https://termly.io/our-terms-of-use/"><u>here</u></a></label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="remember2" id="remember2"> I agree to Privacy Policy <a href="https://termly.io/our-privacy-policy/"><u>here</u></a></label>
                    </div>
                    <div>
                        <img src="<?php echo base_url(); ?>assets/frontend/img/download.png" style="margin-left:88px; height: 97px;width: 254px;" />
                    </div>
                    <div style="margin-top: 20px;;" class="form-group row">
                        <button type="submit" style="margin-left: auto;margin-right:auto;" id="purchaseButton" class="btn btn-primary col-md-8">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- download_modal_start -->
<div class="modal fade" id="downloadModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">

                <h4 class="modal-title float-left">Download</h4>
            </div>
            <div class="modal-body text-center">
                <div class="container">
                    <div class="form-group row">
                        <img style="margin-left: auto;margin-right: auto;;" src="https://img.icons8.com/metro/26/000000/download.png" />
                    </div>
                    <div class="form-group row">
                        <a href="" style="color: black;font-weight: bold;text-decoration: none;margin-left:auto;margin-right:auto;" target="_blank" id="download_link">Cliek Here to Download File</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- download_modal_end -->

<!-- review_modal_start -->
<div class="modal fade" id="reviewModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <h4 class="modal-title float-left">Reviews</h4>
            </div>
            <div class="modal-body text-center">
                <div class="container" id="reviewModalc"> </div>
            </div>
        </div>
    </div>
</div>
<!-- review_modal_end -->

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {


        $('.select2').select2();
        $('.trigger_change_event').change(function() {
            $("#form_filter").submit();
        });

        // AJAX call for autocomplete 
        $(document).ready(function() {

            $('.input-group-append').on('click', function(e) {
                e.preventDefault();
                $('#reset')[0].click();
            })
            $("#search-box").keyup(function() {
                $.ajax({
                    type: "POST",
                    url: "v1/api/autocomplete",
                    data: 'keyword=' + $(this).val(),
                    beforeSend: function() {
                        $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                    },
                    success: function(data) {
                        $("#suggesstion-box").show();
                        data = JSON.parse(data);
                        $("#suggesstion-box").html(data.html);
                        $("#search-box").css("background", "#FFF");
                    }
                });
            });

            $('#download_link').on('click', function(e) {
                e.preventDefault();
                var link = document.createElement("a");
                link.download = $(this).attr('data-name');
                link.href = $(this).attr('data-file_source');
                link.click();

            });
            $('.download_btn').on('click', function(e) {
                e.preventDefault()
                if ($(this).attr('data-login') == 'loginModal') {
                    $('#loginModal').modal('toggle');
                    return false;
                }
                $('#purchaseButton').attr('data-id', $(this).attr('data-id'));
            })


            $('.review_btn').on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: '/v1/api/get_review',
                    dataType: 'JSON',
                    timeout: 5000,
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.status) {
                            var html = '<div class="review-block">';

                            if (response.data.length > 0) {

                                $.each(response.data, function(i, v) {
                                    html += `<div class="row">
                                <div class="col-sm-3">
                                    
                                    <div class="review-block-date">Date : ` + v.created_at + `</div>
                                </div>
                                <div class="col-sm-9">
                                                    
                                <div class="review-block-description">` + (v.comment || 'No Comment') + `</div>
                                </div>
                                </div>
                                <hr/>`;
                                })
                            } else {
                                html += '<center>No Reviews Available</center>';
                            }
                            html += '</div>'
                            $('#reviewModalc').html(html);
                            $('#reviewModal').modal('toggle');
                        }
                    }
                });
            });
        });

        $('#downloadModal').on('hidden.bs.modal', function(e) {
            window.location.reload();
        })
        // const stripe = Stripe("pk_test_51JNKXXLfAB0QE6KXiN4oLo3jYJDkxFDkiA8mn7KUnQCbsKYc0HsyCFzpvYBn1XaqancziO67lthVOARxm371Og9600QbeX1Ovt");
        // const stripe = Stripe("<?php echo $this->_data['stripe_client']; ?>");

        // const elements = stripe.elements();
        // const cardNumberElement = elements.create('cardNumber');
        // const cardExpiryElement = elements.create('cardExpiry');
        // const cardCvcElement    = elements.create('cardCvc');


        // cardNumberElement.mount('#card-number');
        // cardExpiryElement.mount('#card-expiry');
        // cardCvcElement.mount('#card-cvc');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('purchaseButton');
        // const clientSecret   = cardButton.dataset.secret;


        $(document).on('click', '#purchaseButton', function(e) {

            $(window).off("beforeonunload");


        });


        // var payment_method_id = "";
        // cardButton.addEventListener('click', async (e) => 
        // {
        //     if (payment_method_id == "") 
        //     { 
        //         const { setupIntent, error } = await stripe.handleCardSetup(
        //             clientSecret, cardNumberElement, 
        //             {
        //                 payment_method_data: 
        //                 {
        //                     billing_details: 
        //                     {
        //                         name: cardHolderName.value
        //                     }
        //                 }
        //             }
        //         );
        //         if (error) 
        //         {
        //             // Display "error.message" to the user... 
        //             alert(error.message);
        //             console.log(clientSecret)
        //             return false;
        //         }
        //         else
        //         {
        //             payment_method_id = setupIntent.payment_method;

        //             if (payment_method_id != "") 
        //             { 
        //                 // The card has been verified successfully...
        //                 console.log(setupIntent.payment_method);               
        //                 document.getElementById('payment-method').value = payment_method_id;

        //                 $.ajax({
        //                     type: 'POST',
        //                     url: '/v1/api/member/purchase',
        //                     dataType: 'JSON',
        //                     timeout: 900000,
        //                     data: { id:  $('#purchaseButton').attr('data-id'), key: payment_method_id },
        //                     success: function (response) 
        //                     {
        //                         // response= JSON.parse(response);
        //                         console.log(response)
        //                         $('#p_'+$('#purchaseButton').attr('data-id')).after('Already Downloaded');
        //                         $('#p_'+$('#purchaseButton').attr('data-id')).remove();
        //                         if(response.status)
        //                         {
        //                             var link      = document.createElement("a");
        //                             link.download = response.file_name+'.'+response.extension;
        //                             link.href     = response.file_source;
        //                             link.click();

        //                             cardNumberElement.mount('#card-number');
        //                             cardExpiryElement.mount('#card-expiry');
        //                             cardCvcElement.mount('#card-cvc');

        //                             $('#purchaseModal').modal('toggle');
        //                             $('#downloadModal').modal('toggle');
        //                             $('#download_link').attr('href',response.file_source);
        //                             alert('Payment done successfully. Your file is being downloaded...')
        //                         }
        //                         else
        //                         {
        //                             if (response.error_msg) 
        //                             {
        //                                 alert(response.error_msg);
        //                             }else{
        //                                 alert('Error While Making Payment.');
        //                             }

        //                         }
        //                     }
        //                 }); 
        //             }
        //         }
        //     }else if (payment_method_id != "") 
        //     { 
        //         // The card has been verified successfully...
        //         console.log(payment_method_id);               
        //         document.getElementById('payment-method').value = payment_method_id;

        //         $.ajax({
        //             type: 'POST',
        //             url: '/v1/api/member/purchase',
        //             dataType: 'JSON',
        //             timeout: 900000,
        //             data: { id:  $('#purchaseButton').attr('data-id'), key: payment_method_id },
        //             success: function (response) 
        //             {
        //                 // response= JSON.parse(response);
        //                 console.log(response)
        //                 $('#p_'+$('#purchaseButton').attr('data-id')).after('Already Downloaded');
        //                 $('#p_'+$('#purchaseButton').attr('data-id')).remove();
        //                 if(response.status)
        //                 {
        //                     var link      = document.createElement("a");
        //                     link.download = response.file_name+'.'+response.extension;
        //                     link.href     = response.file_source;
        //                     link.click();

        //                     cardNumberElement.mount('#card-number');
        //                     cardExpiryElement.mount('#card-expiry');
        //                     cardCvcElement.mount('#card-cvc');

        //                     $('#purchaseModal').modal('toggle');
        //                     $('#downloadModal').modal('toggle');
        //                     $('#download_link').attr('href',response.file_source);
        //                     alert('Payment done successfully. Your file is being downloaded...')
        //                 }
        //                 else
        //                 {
        //                     if (response.error_msg) 
        //                     {
        //                         alert(response.error_msg);
        //                     }else{
        //                         alert('Error While Making Payment.');
        //                     }
        //                 }
        //             }
        //         }); 
        //     } 
        // });






        var payment_method_id = "";
        cardButton.addEventListener('click', async (e) => {

            if (!$('#remember').prop('checked')) {
                toastr.error('Terms and Conditions is required.');
                return false;
            }

            if (!$('#remember2').prop('checked')) {
                toastr.error('Privacy Policy is required.');
                return false;
            }
            let exp_year = $('#exp_year').val();
            let exp_month = $('#exp_month').val();
            let card_number = $('#cardnumber').val();
            let card_cvc = $('#cvc').val();
            let card_name = $('#card-holder-name').val();
            $.ajax({
                type: 'POST',
                url: '/v1/api/member/purchase',
                dataType: 'JSON',
                timeout: 900000,
                data: {
                    id: $('#purchaseButton').attr('data-id'),
                    exp_month: exp_month,
                    exp_year: exp_year,
                    card_number: card_number,
                    card_cvc: card_cvc,
                    card_name: card_name
                },
                success: function(response) {

                    if (response.status) {
                        $('#p_' + $('#purchaseButton').attr('data-id')).after('Already Downloaded');
                        $('#p_' + $('#purchaseButton').attr('data-id')).remove();
                        var link = document.createElement("a");
                        link.download = response.file_name + '.' + response.extension;
                        link.href = response.file_source;
                        link.click();

                        // cardNumberElement.mount('#card-number');
                        // cardExpiryElement.mount('#card-expiry');
                        // cardCvcElement.mount('#card-cvc');

                        $('#purchaseModal').modal('toggle');
                        // $('#downloadModal').modal('toggle');
                        // $('#download_link').attr('href',response.file_source);
                        toastr.success('Payment done successfully. Your file is being downloaded...')
                    } else {
                        if (response.error_msg) {
                            toastr.error(response.error_msg);
                        } else {
                            toastr.error('Error While Making Payment.');
                        }
                    }
                }
            });
        });

        //To select country name
        function selectCountry(val) {
            $("#search-box").val(val);
            $("#suggesstion-box").hide();
            $("#form_filter").submit();
        }
    }, false)
</script>