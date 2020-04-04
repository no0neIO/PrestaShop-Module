<div class="form-group">
  <form>
    <label for="fname">Item 1:</label><br>
    <input type="text" id="sleed_item1" name="sleed_item1" placeholder="sleed_item1" value={$value1|default:'Nothing
      here...'}><br>
    <label for="lname">Item 2: </label><br>
    <input type="text" id="sleed_item2" name="sleed_item2" placeholder="sleed_item2" value={$value2|default:'Nothing
      here...'}><br>
    <label for="fname">Item 3:</label><br>
    <input type="text" id="sleed_item3" name="sleed_item3" placeholder="sleed_item3" value={$value3|default:'Nothing
      here...'}><br>
  </form>
  {* <div style="margin-top: 50px;">
    <button class="button btn btn-default" id="cancelConfiguratorProduct" name="cancelConfiguratorProduct"
      type="submit"><i class="process-icon-save"></i> {l s='Cancel' mod='configurator'}</button>
    <button class="button btn btn-default pull-right" id="submitConfiguratorProduct" name="submitAddproduct"
      type="submit"><i class="process-icon-save"></i> {l s='Save' mod='configurator'}</button>
  </div> *}
</div>
<div class="panel-footer">
  <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i
      class="process-icon-cancel"></i> {l s='Cancel'}</a>
  <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l
    s='Save'}</button>
  <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i
      class="process-icon-save"></i> {l s='Save and stay'} </button>
</div>