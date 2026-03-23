-- =============================================================================
-- Luxury Marketer: promote former "research" and "research-news" children to
-- top-level categories, then remove the top-level "research" category.
--
-- BACK UP THE DATABASE BEFORE RUNNING.
-- Replace yxdtlw_ with your table prefix if different.
-- Run in MySQL/MariaDB. Review term IDs in phpMyAdmin if unsure.
-- After this, flush permalinks: WP Admin → Settings → Permalinks → Save.
-- Update theme/header links (already adjusted in theme) and add redirects if needed.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1) Promote direct children of top-level category "research" to parent = 0
-- -----------------------------------------------------------------------------
SET @research_term_id := (
  SELECT t.term_id
  FROM yxdtlw_terms t
  INNER JOIN yxdtlw_term_taxonomy tt ON tt.term_id = t.term_id AND tt.taxonomy = 'category'
  WHERE t.slug = 'research' AND tt.parent = 0
  LIMIT 1
);

UPDATE yxdtlw_term_taxonomy
SET parent = 0
WHERE taxonomy = 'category'
  AND @research_term_id IS NOT NULL
  AND parent = @research_term_id;

-- -----------------------------------------------------------------------------
-- 2) Promote direct children of "research-news" (under News) to top level
--    (slug is typically research-news; adjust if your slug differs)
-- -----------------------------------------------------------------------------
SET @research_news_term_id := (
  SELECT t.term_id
  FROM yxdtlw_terms t
  INNER JOIN yxdtlw_term_taxonomy tt ON tt.term_id = t.term_id AND tt.taxonomy = 'category'
  WHERE t.slug = 'research-news'
  LIMIT 1
);

UPDATE yxdtlw_term_taxonomy
SET parent = 0
WHERE taxonomy = 'category'
  AND @research_news_term_id IS NOT NULL
  AND parent = @research_news_term_id;

-- -----------------------------------------------------------------------------
-- 3) Reassign posts that are ONLY in the old top-level "research" category
--    to "research-news" (optional but avoids losing the only term on those posts)
-- -----------------------------------------------------------------------------
SET @research_tt_id := (
  SELECT term_taxonomy_id FROM yxdtlw_term_taxonomy
  WHERE taxonomy = 'category' AND term_id = @research_term_id LIMIT 1
);

SET @research_news_tt_id := (
  SELECT term_taxonomy_id FROM yxdtlw_term_taxonomy
  WHERE taxonomy = 'category' AND term_id = @research_news_term_id LIMIT 1
);

INSERT IGNORE INTO yxdtlw_term_relationships (object_id, term_taxonomy_id, term_order)
SELECT r.object_id, @research_news_tt_id, 0
FROM yxdtlw_term_relationships r
WHERE @research_tt_id IS NOT NULL
  AND @research_news_tt_id IS NOT NULL
  AND r.term_taxonomy_id = @research_tt_id
  AND NOT EXISTS (
    SELECT 1 FROM yxdtlw_term_relationships r2
    WHERE r2.object_id = r.object_id AND r2.term_taxonomy_id = @research_news_tt_id
  );

DELETE FROM yxdtlw_term_relationships
WHERE @research_tt_id IS NOT NULL
  AND term_taxonomy_id = @research_tt_id;

-- -----------------------------------------------------------------------------
-- 4) Delete top-level "research" term (taxonomy row + term row)
-- -----------------------------------------------------------------------------
DELETE FROM yxdtlw_term_taxonomy
WHERE taxonomy = 'category'
  AND term_id = @research_term_id
  AND @research_term_id IS NOT NULL;

DELETE FROM yxdtlw_terms
WHERE term_id = @research_term_id
  AND @research_term_id IS NOT NULL;

-- Optional: recount terms (WordPress will refresh counts on next save; or use a recount plugin)
-- UPDATE yxdtlw_term_taxonomy SET count = (SELECT COUNT(*) FROM yxdtlw_term_relationships tr WHERE tr.term_taxonomy_id = yxdtlw_term_taxonomy.term_taxonomy_id) WHERE taxonomy = 'category';
