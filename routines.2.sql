DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedInvestigationPreParoleFilter`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT, IN `filter_val` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
        SELECT 
        docket_no 
        from F21T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and
        ppo_recommendation LIKE CONCAT("%",filter_val,"%")
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and
        ppo_recommendation LIKE CONCAT("%",filter_val,"%")
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedInvestigationProbationManifest`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 
    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and manifest_date is not null
    ) as temp;
ELSE
    SELECT COUNT(*)  as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1 and ppo_recommendation is not NULL and manifest_date is not null
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionPardonFilter`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT, IN `filter_val` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
        SELECT 
        docket_no 
        from F21T11_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T11_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedInvestigationPreParoleTotal`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F21T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedInvestigationProbationTotal`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionPardonTotal`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F21T11_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T11_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedInvestigationProbationPSIR`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and ppo_recommendation is not NULL 
    ) as temp;
ELSE
    SELECT COUNT(*)
    FROM 
    (SELECT 
        docket_no 
    from F5T2_ACTED where Y_M >= d1 and Y_M <= d2 and status = 1 and ppo_recommendation is not NULL 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionProbationFilter`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT, IN `filter_val` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
        SELECT 
        docket_no 
        from F5T9 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T9 where Y_M >= d1 and Y_M <= d2 and status = 1  and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionParoleFilter`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT, IN `filter_val` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
        SELECT 
        docket_no 
        from F21T11_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T11_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionParoleTotal`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F21T11_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F21T11_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getReferralsReceivedPardonSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T8_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F21T15_RCV_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T8_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F21T15_RCV_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCompletedSupervisionProbationTotal`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
    	SELECT 
        docket_no 
    	from F5T9 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T9 where Y_M >= d1 and Y_M <= d2 and status = 1 
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getReferralsReceivedProbationSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F5T8 where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T13_RCV where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F5T8 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T13_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getCourtDispositionProbationFilter`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT, IN `filter_val` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (
        SELECT 
        docket_no 
        from F5T4 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T4 where Y_M >= d1 and Y_M <= d2 and status = 1  and
        disposed_decision LIKE CONCAT("%",filter_val,"%")
   ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getWorkloadHandledPardonSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T7_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F21T8_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T7_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F21T8_PARDON where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getWorkloadHandledParoleSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T7_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F21T8_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T7_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F21T8_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getWorkloadHandledProbationInv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T1 where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T5 where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T6_RCV where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    ) as temp;
ELSE
	SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_RCV where Y_M >= d1 and Y_M <= d2 and status = 1 
    UNION
    SELECT
        docket_no
    from 
        F5T1 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T5 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T6_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getReferralsReceivedProbationInv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T6_RCV where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT 
        docket_no 
    from F5T2_RCV where Y_M >= d1 and Y_M <= d2 and status = 1 
    UNION
    SELECT
        docket_no
    from 
        F5T6_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getReferralsReceivedParolSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T8_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F21T15_RCV_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1   and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F21T8_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F21T15_RCV_PAROL where Y_M >= d1 and Y_M <= d2 and status = 1  
    ) as temp;
END IF$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`kmsj13`@`%` PROCEDURE `getWorkloadHandledProbationSupv`(IN `d1` TEXT, IN `d2` TEXT, IN `fo` TEXT)
    NO SQL
IF fo != "" AND fo != "ALL" THEN 

    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F5T7 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T8 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T12 where Y_M >= d1 and Y_M <= d2 and status = 1  and field_office = fo
    UNION
    SELECT
        docket_no
    from 
        F5T13_RCV where Y_M >= d1 and Y_M <= d2 and status = 1 and field_office = fo
    ) as temp;
ELSE
    SELECT COUNT(*) as `count`
    FROM 
    (SELECT
        docket_no
    from 
        F5T7 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T8 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T12 where Y_M >= d1 and Y_M <= d2 and status = 1  
    UNION
    SELECT
        docket_no
    from 
        F5T13_RCV where Y_M >= d1 and Y_M <= d2 and status = 1  
        ) as temp;
END IF$$
DELIMITER ;
