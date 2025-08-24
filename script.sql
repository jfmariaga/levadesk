--necesario para potenciar la conssulta de la tabla dashboard 
CREATE INDEX idx_tickets_created_at ON tickets (created_at);
CREATE INDEX idx_tickets_estado_id ON tickets (estado_id);
CREATE INDEX idx_tickets_usuario_id ON tickets (usuario_id);
CREATE INDEX idx_tickets_asignado_a ON tickets (asignado_a);
CREATE INDEX idx_tickets_sociedad_id ON tickets (sociedad_id);
