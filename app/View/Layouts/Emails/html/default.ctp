<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<table style="width: 100%; font-family:adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif; font-size: 14px; margin: 0; color: #666; line-height: 25px;">
		<tr>
			<td>
				<div style="width: 626px">
					<table style="background: #eee; color: #333; width: 100%;">
						<tbody>
							<tr>
								<td align="left" style="width: 150px"><?php echo $this->Html->image('layout/logo.png', array('style' => 'width:145px; margin-top:10px', 'fullBase' => true))?>																										
							</tr>
						</tbody>
					</table>

					<div style="font-family:adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;">
						<?php echo isset($contentLayout) ? 
						$this->renderLayout($this->fetch('content'), $contentLayout) :
					$this->fetch('content'); ?>
					</div>

					<div style="background: #eee; color: #333; width: 100%;">
						<table style="width: 100%;">
							<tbody>
								<tr>									
									<div style="text-align: center; margin-top: 15px; color: #333; font-family:adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;">
										
										<?php echo "Magda Explorer" ?>
									</div>
								</tr>
								<tr>
									<td align="center">
										<div style="text-align: center; margin-top: 15px; color: #333; font-family:adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;">											
											<?php $queryString = "email=".urlencode(Security::rijndael($email,Configure::read('Security.key'),'encrypt')); ?>
											<?php echo $this->Html->link('Unsubscribe', Router::url('/knownEmails/unsubscribe?'.$queryString, true), array('style' => 'color:#333; font-size:12px; text-align:center'))?>
										</div>
									</td>
								</tr>
								<tr>
									<td align="center">
										<div style="text-align: center; margin-top: 15px; color: #333; font-size: 10px; font-family:adobe-garamond-pro-1, adobe-garamond-pro-2, 'Times New Roman', Times, serif;">
											<span style="text-align: center">Copyright &copy;<?php echo date('Y').' '.Configure::read('APP_NAME')?>.
												All rights reserved.
											</span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
