<?php 
require_once JPATH_ROOT . '/modules/mod_jcomments_latest/helper.php';
$baseurl = JURI::base();
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
SPLoader::loadView( 'section' );
?>
<?
$n=count($this->lists);
?>
<div class="project">
<?
if ($n){
    $i=1;
    if($n==3){
    foreach($this->lists as $key=>$item):
        if ($i==1){
?>            
            <div class="leftdiv">
                <div class="mainimg">
                    <a href="<?echo $item->displaylinktitle;?>"> 
                       <img src="<?echo $item->displayimage;?>" title="<?if(($this->limit)==0){echo $item->displaydescription;}?>" />
                    </a>
                </div>
                <div class="lcontent">
                <div class="imgtitle">
                    <div class="avatar" style="height:32px;width:32px;float:left;">
                       <a href="<?echo $item->displaylinkauthor;?>">
                           <img style="width:100%" src="<?echo $item->displayavatar;?>" />
                       </a>
                    </div>
                    <div class="titlecontent">
                       <a href="<?echo $item->displaylinkauthor;?>">
                           <b><?echo ucfirst($item->displayauthor);?></b>
                       </a>
                    </div>
                </div>
                    <div class="desprice" >
                    <?if (($this->limit)>0 && ($item->displaydescription!='')){?>
                      <div class="description" style="overflow:hidden;margin-top: 5px;">
                          <?
                          $upcase =  ucfirst(strip_tags($item->displaydescription));
                          echo mb_substr($upcase,0,$this->limit,'utf-8').'...';
                          ?>
                      </div>   
                    <?}?>
                      <div class="author" style="display:none"> 
                      </div>
                    </div>
               </div>
           </div>
<?        } else{
?>
    <div class="rightdiv">
       <div class="limg">
          <div class="subimg">
              <a href="<?echo $item->displaylinktitle;?>"> 
                       <img src="<?echo $item->displayimage;?>" title="<?if(($this->limit)==0){echo $item->displaydescription;}?>" />
              </a> 
          </div>
       </div>
       <div class="rcontent">
          <div class="imgtitle">
              <div class="avatar" style="height:32px;width:32px;float:left;">
                  <a href="<?echo $item->displaylinkauthor;?>">
                           <img style="width:100%" src="<?echo $item->displayavatar;?>" />
                  </a>
              </div>
              <div class="titlecontent">
                    <a href="<?echo $item->displaylinkauthor;?>">
                           <b><?echo ucfirst($item->displayauthor);?></b>
                    </a>
              </div>
          </div>
          <div class="desprice">
                <?if (($this->limit)>0 && ($item->displaydescription)){?>
                      <div class="description" style="overflow:hidden;margin-top: 5px;">
                          <?
                          $upcase =  ucfirst(strip_tags($item->displaydescription));
                          echo mb_substr($upcase,0,$this->limit,'utf-8').'...';
                          ?>
                      </div>   
                <?}?>
                      <div class="author" style="display:none"> 
                      </div>
          </div>
       </div>
    </div>
<?    
}
    $i=$i + 1;
    endforeach;
    } else {
        foreach ($this->lists as $item) {
            if($i % 2 == 1){
?>
   <div class="ldiv">
      <div class="limg">
         <div class="subimg">
            <a href="<?echo $item->displaylinktitle;?>"> 
                       <img src="<?echo $item->displayimage;?>" title="<?if(($this->limit)==0){echo $item->displaydescription;}?>" />
            </a>
         </div>
      </div>
      <div class="rcontent">
         <div class="imgtitle">
            <div class="avatar" style="height:32px;width:32px;float:left;">
               <a href="<?echo $item->displaylinkauthor;?>">
                  <img style="width:100%" src="<?echo $item->displayavatar;?>" />
               </a>
            </div>
            <div class="titlecontent">
               <a href="<?echo $item->displaylinkauthor;?>">
                   <b><?echo ucfirst($item->displayauthor);?></b>
               </a>
            </div>
         </div>
         <div class="desprice">
            <?if (($this->limit)>0 && ($item->displaydescription)){?>
               <div class="description" style="overflow:hidden;margin-top: 5px;">
            <?
               $upcase =  ucfirst(strip_tags($item->displaydescription));
               echo mb_substr($upcase,0,$this->limit,'utf-8').'...';
            ?>
               </div>   
            <?}?>
               <div class="author" style="display:none"> 
               </div>
         </div>
      </div>
  </div> 
<?    
            } else {
?>
  <div class="rdiv">
     <div class="limg">
        <div class="subimg">
           <a href="<?echo $item->displaylinktitle;?>"> 
               <img src="<?echo $item->displayimage;?>" title="<?if(($this->limit)==0){echo $item->displaydescription;}?>" />
           </a>
        </div>
     </div>
     <div class="rcontent">
        <div class="imgtitle">
           <div class="avatar" style="height:32px;width:32px;float:left;">
              <a href="<?echo $item->displaylinkauthor;?>">
                  <img style="width:100%" src="<?echo $item->displayavatar;?>" />
              </a>
           </div>
           <div class="titlecontent">
               <a href="<?echo $item->displaylinkauthor;?>">
                   <b><?echo ucfirst($item->displayauthor);?></b>
               </a>
           </div>
        </div>
        <div class="desprice">
            <?if (($this->limit)>0 && ($item->displaydescription)){?>
               <div class="description" style="overflow:hidden;margin-top: 5px;">
            <?
               $upcase =  ucfirst(strip_tags($item->displaydescription));
               echo mb_substr($upcase,0,$this->limit,'utf-8').'...';
            ?>
               </div>   
            <?}?>
               <div class="author" style="display:none"> 
               </div>
        </div>
     </div>
  </div>
<?    
            }
            $i=$i+1;
        }
    }
} else{
    echo 'Updating...';
}
?>
</div>