<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <h5 class="primaryHeading2 d-flex justify-content-between mt-2 my-4 ml-3">
        Review
        <span class=""></span>
    </h5>

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body pb-3 pl-3 pr-3">

                <?php if (($this->_data['status_'] ?? '') == 'success') { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $this->_data['status_msg']; ?>
                    </div>
                <?php } ?>
                <?php if (($this->_data['status_'] ?? '') == 'error') { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->_data['status_msg']; ?>
                    </div>
                <?php } ?>

                <form method="get" action="<?php echo base_url(); ?>member/post_review/<?php echo $this->uri->segment(3) . '/' . $this->uri->segment(4); ?>">
                    <input type="hidden" name="inventory_id" value="<?php echo $this->uri->segment(3) ?? ($_GET['inventory_id'] ?? ''); ?>" />
                    <div class="form-group col-md-5 col-sm-12">
                        <h5 class="primaryHeading2 ">
                            Comment
                        </h5>
                        <textarea name="comment" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-5 col-sm-12">
                        <h5 class="primaryHeading2 ">
                            Private Review
                        </h5>
                        <textarea name="private_review" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-5 col-sm-12">
                        <h5 class="primaryHeading2 ">
                            Review
                        </h5>
                        <select name="review" required class="form-control ">
                            <option value="1">1/10</option>
                            <option value="2">2/10</option>
                            <option value="3">3/10</option>
                            <option value="4">4/10</option>
                            <option value="5">5/10</option>
                            <option value="6">6/10</option>
                            <option value="7">7/10</option>
                            <option value="8">8/10</option>
                            <option value="9">9/10</option>
                            <option value="10">10/10</option>
                        </select>
                    </div>
                    <div class="form-group col-md-5 col-sm-12">
                        <input class="btn btn-primary" type="submit" name="submit" value="Save" />

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>