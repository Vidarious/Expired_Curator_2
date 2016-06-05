<div class="col-md-4">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <th>#</th>
        <th>Varible</th>
        <th>Value</th>

        <tr>
            <td>1</td>
            <td>$_SESSION['Curator_Status']</td>
            <td><?=$theCurator->Session->getValue('Curator_Status')?></td>
        </tr>

        <tr>
            <td>2</td>
            <td>Curator Language</td>
            <td><?=$theCurator->Session->getValue('Curator_Lang')?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>User IP</td>
            <td><?= $theCurator->Session->userIP ?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>Form Token</td>
            <td><?php if($theCurator->Session->getValue('Curator_formToken') !== NULL){ echo $theCurator->Session->getValue('Curator_formToken'); }?></td>
        </tr>
    </table>
</div>

<div class="col-md-4">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <th>#</th>
        <th>Varible</th>
        <th>Value</th>

        <tr>
            <td>1</td>
            <td>TIME()</td>
            <td><?=(date("h:i:s", time()))?></td>
        </tr>

        <tr>
            <td>2</td>
            <td>Timeout Setting</td>
            <td><?=(date("i:s", \Curator\Config\SESSION\TIMEOUT))?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>Regenerate Time Setting</td>
            <td><?=(date("i:s", \Curator\Config\SESSION\REGENERATE\TIME))?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>Regenerate Random Number</td>
            <td><?php if($theCurator->Session->getValue('RandomTEST') !== NULL){ echo $theCurator->Session->getValue('RandomTEST'); }?></td>
        </tr>
    </table>
</div>

<div class="col-md-4">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <th>#</th>
        <th>Varible</th>
        <th>Value</th>

        <tr>
            <td>4</td>
            <td>$_SESSION['Curator_startTime']</td>
            <td><?=(date("i:s", time() - $theCurator->Session->getValue('Curator_startTime')))?></td>
        </tr>
        
        <tr>
            <td>5</td>
            <td>$_SESSION['Curator_idleTime']</td>
            
            <td><?php if($theCurator->Session->getValue('RandomTEST') !== NULL){ echo (date("i:s", time() - $theCurator->Session->getValue('Curator_idleTime2'))); }?></td>
        </tr>
        
        <tr>
            <td>6</td>
            <td>$_SESSION['Curator_regenTime']</td>
            <td><?=(date("i:s", time() -  $theCurator->Session->getValue('Curator_regenTime')))?></td>
        </tr>
    </table>
</div>

<div class="col-md-12">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <th>#</th>
        <th>Varible</th>
        <th>Value</th>
        
        <tr>
            <td>1</td>
            <td>Session ID</td>
            <td><?=session_id()?></td>
        </tr>
        
        <tr>
            <td>2</td>
            <td>$_SESSION['Curator_userAgent']</td>
            <td><?=$theCurator->Session->getValue('Curator_userAgent')?></td>
        </tr>
        
        <tr>
            <td>3</td>
            <td>$_SESSION['Curator_userKey']</td>
            <td><?=$theCurator->Session->getValue('Curator_userKey')?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>Current Page</td>
            <td><?=$theCurator->Session->getValue('Curator_PageCurrent')?></td>
        </tr>

        <tr>
            <td>3</td>
            <td>Previous Page</td>
            <td><?=$theCurator->Session->getValue('Curator_PagePrevious')?></td>
        </tr>
    </table>
</div>