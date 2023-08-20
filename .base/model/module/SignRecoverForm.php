<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
MODULE("Form");
class SignRecoverForm extends Form{
	public $Action = null;
	public $Title = "Account Recovery";
	public $Image = "undo-alt";
	public $SubmitLabel = "Submit";
	public $SignatureLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-sign-in text-muted'></i>
		</span>";
	public $PasswordLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $PasswordConfirmationLabel = "
		<span class='input-group-text bg-white px-4 border-md border-right-0'>
			<i class='fa fa-lock text-muted'></i>
		</span>";
	public $SignaturePlaceHolder = "Email/Phone";
	public $PasswordPlaceHolder = "Password";
	public $PasswordConfirmationPlaceHolder = "Confirm Password";

	public function __construct(){
        parent::__construct();
		$this->Action = \MiMFa\Library\User::$RecoverHandlerPath;
		$this->SuccessPath = \MiMFa\Library\User::$InHandlerPath;
	}

	public function EchoFields(){
		if(isValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey)):
			echo '<input type="hidden" name="'.\MiMFa\Library\User::$RecoveryRequestKey.'" value="'.getValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey).'">';
?>
			<div class="row">
				<!-- Password -->
				<div class="input-group col-lg-12 mb-4">
                    <label for="Password" class="input-group-prepend">
                        <?php echo $this->PasswordLabel; ?>
                    </label>
                    <input id="Password" name="Password" type="password" placeholder="<?php echo __($this->PasswordPlaceHolder);?>" aria-label="<?php echo __($this->PasswordPlaceHolder);?>" class="form-control bg-white border-left-0 border-md" />
				</div>

				<!-- PasswordConfirmation -->
				<div class="input-group col-lg-12 mb-4">
					<label for="PasswordConfirmation" class="input-group-prepend">
						<?php echo $this->PasswordConfirmationLabel; ?>
					</label>
                    <input id="PasswordConfirmation" name="PasswordConfirmation" type="password" placeholder="<?php echo __($this->PasswordConfirmationPlaceHolder);?>" aria-label="<?php echo __($this->PasswordConfirmationPlaceHolder);?>" class="form-control bg-white border-left-0 border-md" />
				</div>
			</div>
			<?php
		else:?>
			<div class="row">
				<!-- Signature Address -->
				<div class="input-group col-lg-12 mb-4">
					<label for="Signature" class="input-group-prepend">
						<?php echo $this->SignatureLabel; ?>
					</label>
                    <input id="Signature" name="Signature" type="text" autocomplete="username"
                        placeholder="<?php echo __($this->SignaturePlaceHolder);?>"
                        aria-label="<?php echo __($this->SignaturePlaceHolder);?>"
                        class="form-control bg-white border-left-0 border-md" />
				</div>
			</div>
		<?php endif;
    }
	
	public function EchoScript(){
		?>
		<script>
			$(function () {
                $(".<?php echo $this->Name ?> form").submit(function(e) {
					if ($(".<?php echo $this->Name ?> form #PasswordConfirmation").val() == $(".<?php echo $this->Name ?> form #Password").val()) return true;
					$(".<?php echo $this->Name ?> form result").remove();
					$(".<?php echo $this->Name ?> form").append(Html.error("New password and confirm password does not match!"));
					e.preventDefault();
					return false;
                });
			});
		</script>
		<?php
		parent::EchoScript();
    }

	public function Action(){
		$_req = $_REQUEST;
		switch(strtolower($this->Method)){
            case "get":
				$_req = $_GET;
			break;
            case "post":
				$_req = $_POST;
			break;
        }
		try {
			if(isValid($_req,"Password") && isValid($_REQUEST, \MiMFa\Library\User::$RecoveryRequestKey)){
				$res = \_::$INFO->User->ReceiveRecoveryLink();
				if($res === true)
                	echo HTML::Success("Dear '".\_::$INFO->User->TemporaryName."', your password changed successfully!");
				elseif($res === false)
					echo HTML::Error("There a problem is occured!");
				else
					echo HTML::Error($res);
			}
			elseif(isValid($_req,"Signature")){
				\_::$INFO->User->Find(getValid($_req,"Signature"));
				$res = \_::$INFO->User->SendRecoveryEmail();
				if($res === true)
                	echo HTML::Success("Dear user, the reset password sent to your email successfully!");
				elseif($res === false)
					echo HTML::Error("There a problem is occured!");
				else
					echo HTML::Error($res);
			}
			else echo HTML::Warning("Please fill fields correctly!");
		} catch(\Exception $ex) { echo HTML::Error($ex); }
    }
}
?>