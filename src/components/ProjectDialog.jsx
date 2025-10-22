import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from './ui/dialog';
import { Badge } from './ui/badge';
import { ImageWithFallback } from './figma/ImageWithFallback';
import { ExternalLink } from 'lucide-react';

// Project type is now just a JavaScript object

export function ProjectDialog({ project, open, onOpenChange }) {
  if (!project) return null;

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-3xl max-h-[90vh] overflow-y-auto bg-card border-2 border-primary/30">
        <DialogHeader>
          <div className="aspect-video overflow-hidden bg-muted mb-4 relative border-2 border-primary/20">
            <ImageWithFallback
              src={project.image}
              alt={project.title}
              className="w-full h-full object-cover"
            />
            {/* Corner decoration */}
            <div className="absolute top-0 right-0 w-12 h-12 border-t-2 border-r-2 border-secondary"></div>
          </div>
          <DialogTitle className="text-2xl text-foreground">{project.title}</DialogTitle>
          <DialogDescription>
            <Badge variant="secondary" className="mt-2 bg-primary/10 text-primary border border-primary/30">
              {project.category}
            </Badge>
          </DialogDescription>
        </DialogHeader>
        
        <div className="space-y-6 mt-4">
          <div className="relative pl-4 border-l-2 border-primary">
            <h4 className="mb-2 text-foreground">Overview</h4>
            <p className="text-muted-foreground">{project.longDescription}</p>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="p-4 border border-primary/20 bg-primary/5">
              <h4 className="mb-2 text-foreground text-sm uppercase tracking-wider">Role</h4>
              <p className="text-muted-foreground">{project.role}</p>
            </div>
            <div className="p-4 border border-accent/20 bg-accent/5">
              <h4 className="mb-2 text-foreground text-sm uppercase tracking-wider">Timeline</h4>
              <p className="text-muted-foreground">{project.timeline}</p>
            </div>
          </div>

          <div>
            <h4 className="mb-3 text-foreground">Technologies & Skills</h4>
            <div className="flex flex-wrap gap-2">
              {project.tags.map((tag, index) => (
                <Badge key={index} variant="outline" className="border-secondary/30 text-secondary hover:border-secondary">
                  {tag}
                </Badge>
              ))}
            </div>
          </div>

          {project.link && (
            <div className="pt-4 border-t border-primary/20">
              <a
                href={project.link}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors"
              >
                <ExternalLink size={16} />
                View Project
              </a>
            </div>
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
}


